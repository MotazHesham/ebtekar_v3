<?php

namespace Modules\Reports\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Returns\Entities\ReturnCase;
use Modules\Settlement\Entities\Settlement;
use Modules\Shipping\Entities\Shipment;
use Modules\Shipping\Enums\ShipmentStatus;

class ShippingReportService
{
    public function resolveRole(?User $user = null): string
    {
        $user = $user ?: auth()->user();

        if (! $user || $user->is_admin || $user->user_type === 'staff') {
            return 'admin';
        }

        return match ($user->user_type) {
            'shipping_partner', 'receiving_clerk' => 'partner',
            'courier', 'delivery_man'             => 'courier',
            'dispatcher'                          => 'dispatcher',
            default                               => 'admin',
        };
    }

    public function dashboardFor(?User $user = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $role = $this->resolveRole($user);

        return match ($role) {
            'partner'    => $this->partnerDashboard($user, $dateFrom, $dateTo),
            'courier'    => $this->courierDashboard($user, $dateFrom, $dateTo),
            'dispatcher' => $this->dispatcherDashboard($user, $dateFrom, $dateTo),
            default      => $this->adminDashboard($user, $dateFrom, $dateTo),
        };
    }

    public function adminDashboard(?User $user = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $base = $this->scopedQuery($user, $dateFrom, $dateTo);

        return [
            'role'     => 'admin',
            'cards'    => [
                $this->card('total', __('reports::cards.total'), (clone $base)->count()),
                $this->card('handed', __('reports::cards.handed'), (clone $base)->where('status', ShipmentStatus::HandedToPartner->value)->count()),
                $this->card('received', __('reports::cards.received'), (clone $base)->where('status', ShipmentStatus::ReceivedAtWarehouse->value)->count()),
                $this->card('out', __('reports::cards.out'), (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->count()),
                $this->card('delivered', __('reports::cards.delivered'), (clone $base)->where('status', ShipmentStatus::Delivered->value)->count()),
                $this->card('returns', __('reports::cards.returns'), (clone $base)->whereIn('status', [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])->count()),
                $this->card('cod', __('reports::cards.cod'), number_format((float) (clone $base)->sum('remaining_cod'), 2)),
            ],
            'by_status' => $this->statusBreakdown($base),
            'recent'    => (clone $base)->orderByDesc('id')->limit(10)->get(['id', 'order_num', 'status', 'client_name', 'remaining_cod', 'last_status_at']),
        ];
    }

    public function partnerDashboard(?User $user = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $base = $this->scopedQuery($user, $dateFrom, $dateTo);

        return [
            'role'  => 'partner',
            'cards' => [
                $this->card('awaiting_receive', __('reports::cards.awaiting_receive'),
                    (clone $base)->where('status', ShipmentStatus::HandedToPartner->value)->count()),
                $this->card('in_warehouse', __('reports::cards.in_warehouse'),
                    (clone $base)->where('status', ShipmentStatus::ReceivedAtWarehouse->value)->count()),
                $this->card('out', __('reports::cards.out'),
                    (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->count()),
                $this->card('delivered', __('reports::cards.delivered_today'),
                    (clone $base)->where('status', ShipmentStatus::Delivered->value)->whereDate('delivered_at', today())->count()),
                $this->card('returns', __('reports::cards.returns'),
                    (clone $base)->whereIn('status', [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])->count()),
            ],
            'by_status' => $this->statusBreakdown($base),
            'recent'    => (clone $base)->orderByDesc('id')->limit(8)->get(['id', 'order_num', 'status', 'governorate', 'last_status_at']),
        ];
    }

    public function courierDashboard(?User $user = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $base = $this->scopedQuery($user, $dateFrom, $dateTo);

        return [
            'role'  => 'courier',
            'cards' => [
                $this->card('active', __('reports::cards.my_active'),
                    (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->count()),
                $this->card('delivered', __('reports::cards.delivered_today'),
                    (clone $base)->where('status', ShipmentStatus::Delivered->value)->whereDate('delivered_at', today())->count()),
                $this->card('returns', __('reports::cards.my_returns'),
                    (clone $base)->whereIn('status', [ShipmentStatus::Returned->value, ShipmentStatus::Refused->value])
                        ->whereDate('returned_at', today())->count()),
                $this->card('cod', __('reports::cards.cod_to_collect'),
                    number_format((float) (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->sum('remaining_cod'), 2)),
            ],
            'by_status' => $this->statusBreakdown($base),
            'recent'    => (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)
                ->orderByDesc('out_with_courier_at')->limit(10)
                ->get(['id', 'order_num', 'client_name', 'phone_number', 'remaining_cod', 'governorate']),
        ];
    }

    public function dispatcherDashboard(?User $user = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $base = $this->scopedQuery($user, $dateFrom, $dateTo);

        $queue = (clone $base)->where('status', ShipmentStatus::ReceivedAtWarehouse->value)->count();

        return [
            'role'  => 'dispatcher',
            'cards' => [
                $this->card('dispatch_queue', __('reports::cards.dispatch_queue'), $queue),
                $this->card('out', __('reports::cards.out'), (clone $base)->where('status', ShipmentStatus::OutWithCourier->value)->count()),
                $this->card('delivered', __('reports::cards.delivered_today'),
                    (clone $base)->where('status', ShipmentStatus::Delivered->value)->whereDate('delivered_at', today())->count()),
                $this->card('open_returns', __('reports::cards.open_returns'),
                    ReturnCase::where('status', 'open')->count()),
                $this->card('pending_settlements', __('reports::cards.pending_settlements'),
                    Settlement::where('status', 'pending')->count()),
            ],
            'by_status' => $this->statusBreakdown($base),
            'recent'    => (clone $base)->where('status', ShipmentStatus::ReceivedAtWarehouse->value)
                ->orderByDesc('received_by_partner_at')->limit(10)
                ->get(['id', 'order_num', 'governorate', 'remaining_cod', 'shipping_partner_id']),
        ];
    }

    protected function scopedQuery(?User $user, ?string $dateFrom, ?string $dateTo)
    {
        $query = Shipment::query()->forUser($user);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', Carbon::parse($dateFrom)->toDateString());
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', Carbon::parse($dateTo)->toDateString());
        }

        return $query;
    }

    protected function statusBreakdown($base): array
    {
        return (clone $base)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();
    }

    protected function card(string $key, string $label, mixed $value): array
    {
        return ['key' => $key, 'label' => $label, 'value' => $value];
    }
}
