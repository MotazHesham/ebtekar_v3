<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\MarketerWalletTransaction;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class MarketerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $marketer = Marketer::where('user_id', $user->id)->firstOrFail();

        $orders = Order::withoutGlobalScope('completed')
            ->with(['marketerAttribution'])
            ->whereHas('marketerAttribution', function ($query) use ($marketer) {
                $query->where('marketer_id', $marketer->id);
            });

        $totalOrders = (clone $orders)->count();
        $totalSales = (clone $orders)->sum('total_cost');
        $deliveredOrders = (clone $orders)->where('delivery_status', 'delivered')->where('returned', 0)->count();
        $cancelledOrReturnedOrders = (clone $orders)->where(function ($query) {
            $query->where('delivery_status', 'cancel')->orWhere('returned', 1);
        })->count();
        $customersCount = (clone $orders)->distinct('phone_number')->count('phone_number');

        $totalCommission = (float) $marketer->orderAttributions()->sum('commission_amount');
        $paidCommission = (float) $marketer->orderAttributions()->whereNotNull('paid_at')->sum('commission_amount');
        $remainingCommission = $totalCommission - $paidCommission;
        $conversionRate = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 2) : 0;

        $recentOrders = (clone $orders)->latest()->take(20)->get();

        return view('frontend.marketer.dashboard', compact(
            'marketer',
            'totalOrders',
            'totalSales',
            'totalCommission',
            'paidCommission',
            'remainingCommission',
            'customersCount',
            'conversionRate',
            'deliveredOrders',
            'cancelledOrReturnedOrders',
            'recentOrders'
        ));
    }

    public function statement()
    {
        $user = Auth::user();
        $marketer = Marketer::where('user_id', $user->id)->firstOrFail();

        $transactionsQuery = MarketerWalletTransaction::where('marketer_id', $marketer->id);

        $totalCredits = (float) (clone $transactionsQuery)
            ->where('amount', '>', 0)
            ->sum('amount');

        $totalDebits = (float) abs((clone $transactionsQuery)
            ->where('amount', '<', 0)
            ->sum('amount'));

        $transactionsCount = (clone $transactionsQuery)->count();
        $currentBalance = (float) ((clone $transactionsQuery)->latest('id')->value('balance_after') ?? 0);
        $netBalance = $totalCredits - $totalDebits;

        $transactions = (clone $transactionsQuery)
            ->latest()
            ->paginate(20);

        return view('frontend.marketer.statement', compact(
            'marketer',
            'transactions',
            'totalCredits',
            'totalDebits',
            'transactionsCount',
            'currentBalance',
            'netBalance'
        ));
    }
}
