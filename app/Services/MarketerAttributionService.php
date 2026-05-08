<?php

namespace App\Services;

use App\Models\CustomerMarketerAttribution;
use App\Models\Marketer;
use App\Models\MarketerWalletTransaction;
use App\Models\Order;
use App\Models\OrderMarketerAttribution;
use App\Models\ReferralVisit;
use App\Models\User;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketerAttributionService
{
    public const REF_COOKIE = 'mk_ref_code';
    public const VISITOR_COOKIE = 'mk_vid';

    public function captureVisit(Request $request, ?WebsiteSetting $website = null): ?Marketer
    {
        $refCode = trim((string) $request->query('ref', ''));
        if ($refCode === '') {
            return null;
        }

        $marketer = Marketer::query()
            ->where('code', $refCode)
            ->where('is_active', true)
            ->when($website, function ($query) use ($website) {
                $query->where(function ($inner) use ($website) {
                    $inner->whereNull('website_setting_id')->orWhere('website_setting_id', $website->id);
                });
            })
            ->first();

        if (!$marketer) {
            return null;
        }

        $cookieId = $request->cookie(self::VISITOR_COOKIE) ?: (string) Str::uuid();
        $sessionId = $request->session()->getId();
        $now = now();

        ReferralVisit::create([
            'marketer_id' => $marketer->id,
            'website_setting_id' => $website->id ?? null,
            'ref_code' => $marketer->code,
            'cookie_id' => $cookieId,
            'session_id' => $sessionId,
            'ip' => $request->ip(),
            'device' => $this->detectDevice($request->userAgent()),
            'browser' => $this->detectBrowser($request->userAgent()),
            'user_agent' => $request->userAgent(),
            'landing_url' => $request->fullUrl(),
            'utm_source' => $request->query('utm_source'),
            'utm_campaign' => $request->query('utm_campaign'),
            'first_seen_at' => $now,
            'last_seen_at' => $now,
        ]);

        $request->session()->put(self::REF_COOKIE, $marketer->code);
        $request->attributes->set('marketer_cookie_id', $cookieId);

        return $marketer;
    }

    public function resolveForCheckout(Request $request, ?User $user, ?WebsiteSetting $website = null): ?array
    {
        $marketer = null;
        $source = 'link';

        $refCode = trim((string) $request->query('ref', ''));
        if ($refCode !== '') {
            $marketer = Marketer::where('code', $refCode)->where('is_active', true)->first();
        }

        if (!$marketer) {
            $cookieCode = $request->cookie(self::REF_COOKIE) ?: $request->session()->get(self::REF_COOKIE);
            if ($cookieCode) {
                $marketer = Marketer::where('code', $cookieCode)->where('is_active', true)->first();
            }
        }

        if (!$marketer) {
            return null;
        }

        $customerIdentifier = $this->resolveCustomerIdentifier($user, $request->phone_number);
        if (!$customerIdentifier) {
            return ['marketer' => $marketer, 'attribution' => null, 'source' => $source];
        }

        $attribution = $this->assignCustomerAttribution($customerIdentifier, $marketer, $website);

        return ['marketer' => $attribution->marketer ?? $marketer, 'attribution' => $attribution, 'source' => $source];
    }

    public function attachOrderAttribution(Order $order, Marketer $marketer, ?CustomerMarketerAttribution $attribution = null, string $source = 'link'): OrderMarketerAttribution
    {
        return OrderMarketerAttribution::updateOrCreate(
            ['order_id' => $order->id],
            [
                'marketer_id' => $marketer->id,
                'customer_marketer_attribution_id' => $attribution->id ?? null,
                'source' => $source,
                'commission_status' => 'pending',
            ]
        );
    }

    public function refreshOrderCommission(Order $order): ?OrderMarketerAttribution
    {
        $orderAttribution = OrderMarketerAttribution::where('order_id', $order->id)->first();
        if (!$orderAttribution) {
            return null;
        }

        $marketer = Marketer::find($orderAttribution->marketer_id);
        if (!$marketer) {
            return null;
        }

        $base = max(((float) $order->total_cost - (float) $order->shipping_country_cost), 0);
        $rate = (float) $marketer->commission_rate;
        $amount = round($base * ($rate / 100), 2);

        $orderAttribution->commission_base = $base;
        $orderAttribution->commission_rate = $rate;
        $orderAttribution->commission_amount = $amount;

        if ($this->isRejected($order)) {
            $orderAttribution->commission_status = 'rejected';
            $orderAttribution->rejected_reason = $order->returned ? 'returned' : 'cancelled';
            $orderAttribution->save();
            return $orderAttribution;
        }

        if ($this->isApproved($order)) {
            $orderAttribution->commission_status = 'approved';
            $orderAttribution->approved_at = $orderAttribution->approved_at ?: now();
            $orderAttribution->rejected_reason = null;
            $orderAttribution->save();

            if (!$orderAttribution->credited_at && $amount > 0) {
                $this->creditMarketer($marketer->id, $amount, $order->id);
                $orderAttribution->credited_at = now();
                $orderAttribution->save();
            }

            return $orderAttribution;
        }

        $orderAttribution->commission_status = 'pending';
        $orderAttribution->rejected_reason = null;
        $orderAttribution->save();

        return $orderAttribution;
    }

    public function resolveCookieLifetimeMinutes(?WebsiteSetting $website): int
    {
        if (!$website || !$website->marketer_attribution_window_days) {
            return 60 * 24 * 60;
        }

        return (int) $website->marketer_attribution_window_days * 24 * 60;
    }

    private function assignCustomerAttribution(string $identifier, Marketer $marketer, ?WebsiteSetting $website): CustomerMarketerAttribution
    {
        $policy = $website->marketer_attribution_policy ?? 'first_click';
        $existing = CustomerMarketerAttribution::query()
            ->where('customer_identifier', $identifier)
            ->when($website, fn($query) => $query->where('website_setting_id', $website->id))
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->latest('assigned_at')
            ->first();

        if ($existing && $existing->is_locked) {
            return $existing;
        }

        if ($existing && $policy === 'first_click') {
            return $existing;
        }

        $expiresAt = null;
        if ($website && $website->marketer_attribution_window_days) {
            $expiresAt = Carbon::now()->addDays((int) $website->marketer_attribution_window_days);
        }

        if ($existing) {
            $existing->update([
                'marketer_id' => $marketer->id,
                'source' => 'link',
                'priority_rule_snapshot' => $policy,
                'assigned_at' => now(),
                'expires_at' => $expiresAt,
            ]);
            return $existing->fresh();
        }

        return CustomerMarketerAttribution::create([
            'marketer_id' => $marketer->id,
            'website_setting_id' => $website->id ?? null,
            'customer_identifier' => $identifier,
            'source' => 'link',
            'priority_rule_snapshot' => $policy,
            'assigned_at' => now(),
            'expires_at' => $expiresAt,
            'is_locked' => false,
        ]);
    }

    private function creditMarketer(int $marketerId, float $amount, int $orderId): void
    {
        $lastBalance = (float) MarketerWalletTransaction::query()
            ->where('marketer_id', $marketerId)
            ->latest('id')
            ->value('balance_after');

        MarketerWalletTransaction::create([
            'marketer_id' => $marketerId,
            'type' => 'commission_credit',
            'amount' => $amount,
            'balance_after' => round($lastBalance + $amount, 2),
            'reference_type' => 'order',
            'reference_id' => $orderId,
            'notes' => 'Auto credit for delivered order',
        ]);
    }

    private function resolveCustomerIdentifier(?User $user, ?string $phoneNumber): ?string
    {
        if ($user) {
            return 'user:' . $user->id;
        }

        if ($phoneNumber) {
            return 'phone:' . preg_replace('/\s+/', '', $phoneNumber);
        }

        return null;
    }

    private function isApproved(Order $order): bool
    {
        return $order->delivery_status === 'delivered' || $order->done == 1;
    }

    private function isRejected(Order $order): bool
    {
        return $order->delivery_status === 'cancel' || $order->returned == 1;
    }

    private function detectDevice(?string $userAgent): string
    {
        $ua = strtolower((string) $userAgent);
        if (Str::contains($ua, ['mobile', 'android', 'iphone'])) {
            return 'mobile';
        }
        if (Str::contains($ua, ['ipad', 'tablet'])) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectBrowser(?string $userAgent): string
    {
        $ua = strtolower((string) $userAgent);
        if (Str::contains($ua, 'edg')) {
            return 'edge';
        }
        if (Str::contains($ua, 'chrome')) {
            return 'chrome';
        }
        if (Str::contains($ua, 'firefox')) {
            return 'firefox';
        }
        if (Str::contains($ua, 'safari')) {
            return 'safari';
        }
        return 'other';
    }
}
