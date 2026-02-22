<?php

namespace App\Jobs;

use App\Models\CombinedOrder;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RecalculateAdsAccountHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $adHistory;

    public function __construct($adHistory)
    {
        $this->adHistory = $adHistory;
    }

    public function handle()
    {
        $adHistory = $this->adHistory;

        // Count combined orders without loading them
        $totalCombinedOrders = CombinedOrder::where('ad_history_id', $adHistory->id)->count();

        // Aggregate orders by status in SQL
        $orderStats = Order::query()
            ->selectRaw("
                orders.order_status,
                COUNT(*) as count,
                SUM(
                    orders.grand_total -
                    COALESCE((
                        SELECT SUM(shipping_cost)
                        FROM order_details
                        WHERE order_details.order_id = orders.id
                    ), 0)
                ) as total_sales_without_shipping,
                SUM(
                    orders.grand_total
                ) as total_sales
            ")
            ->whereIn('orders.combined_order_id', function ($q) use ($adHistory) {
                $q->select('id')
                    ->from('combined_orders')
                    ->where('ad_history_id', $adHistory->id);
            })
            ->groupBy('orders.order_status')
            ->get()
            ->keyBy('order_status');

        // Helper to safely read stats
        $stat = function ($status, $field) use ($orderStats) {
            return $orderStats[$status]->$field ?? 0;
        };

        $sales = [];

        $sales['total_combined_orders'] = $totalCombinedOrders;

        $sales['total_orders'] =
            $orderStats->sum('count');

        $sales['total_orders_sales'] =
            $orderStats->sum('total_sales');

        $sales['total_orders_sales_without_shipping'] =
            $orderStats->sum('total_sales_without_shipping');

        // Pending group
        $sales['pending_count'] =
            $stat('pending', 'count') +
            $stat('awaiting_confirmation', 'count');

        $sales['pending_total_sales'] =
            $stat('pending', 'total_sales') +
            $stat('awaiting_confirmation', 'total_sales');

        $sales['pending_total_sales_without_shipping'] =
            $stat('pending', 'total_sales_without_shipping') +
            $stat('awaiting_confirmation', 'total_sales_without_shipping');

        // Other statuses
        $sales['shipped_count'] = $stat('shipped', 'count') +
            $stat('confirmed', 'count');
        $sales['shipped_total_sales'] = $stat('shipped', 'total_sales') +
            $stat('confirmed', 'total_sales');
        $sales['shipped_total_sales_without_shipping'] = $stat('shipped', 'total_sales_without_shipping') +
            $stat('confirmed', 'total_sales_without_shipping');

        $sales['delivered_count'] = $stat('delivered', 'count');
        $sales['delivered_total_sales'] = $stat('delivered', 'total_sales');
        $sales['delivered_total_sales_without_shipping'] = $stat('delivered', 'total_sales_without_shipping');

        $sales['cancelled_count'] = $stat('cancelled', 'count');
        $sales['cancelled_total_sales'] = $stat('cancelled', 'total_sales');
        $sales['cancelled_total_sales_without_shipping'] = $stat('cancelled', 'total_sales_without_shipping');

        // Assign array; Laravel's 'array' cast will encode once when saving
        $adHistory->sales = $sales;
        $adHistory->save();
    }
}
