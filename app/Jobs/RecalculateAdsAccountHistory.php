<?php

namespace App\Jobs;

use App\Models\ReceiptSocial;
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

        // ReceiptSocial status: pending (confirm=0 done=0 returned=0), confirmed (confirm=1 done=0 return=0),
        // delivered (done=1), returned (returned=1). total_sales = total_cost + shipping_country_cost, total_sales_without_shipping = total_cost.
        $statusCase = "CASE
            WHEN receipt_socials.returned = 1 THEN 'returned'
            WHEN receipt_socials.done = 1 THEN 'delivered'
            WHEN receipt_socials.confirm = 1 THEN 'confirmed'
            ELSE 'pending'
        END";

        $orderStats = ReceiptSocial::query()
            ->selectRaw("
                {$statusCase} as order_status,
                COUNT(*) as count,
                SUM(COALESCE(receipt_socials.total_cost, 0)) as total_sales_without_shipping,
                SUM(COALESCE(receipt_socials.total_cost, 0) + COALESCE(receipt_socials.shipping_country_cost, 0)) as total_sales
            ")
            ->where('receipt_socials.ad_history_id', $adHistory->id)
            ->groupBy(DB::raw($statusCase))
            ->get()
            ->keyBy('order_status');

        // Helper to safely read stats
        $stat = function ($status, $field) use ($orderStats) {
            return $orderStats[$status]->$field ?? 0;
        };

        $sales = [];

        $sales['total_orders'] = $orderStats->sum('count');
        $sales['total_orders_sales'] = $orderStats->sum('total_sales');
        $sales['total_orders_sales_without_shipping'] = $orderStats->sum('total_sales_without_shipping');

        // Pending: confirm=0, done=0, returned=0
        $sales['pending_count'] = $stat('pending', 'count');
        $sales['pending_total_sales'] = $stat('pending', 'total_sales');
        $sales['pending_total_sales_without_shipping'] = $stat('pending', 'total_sales_without_shipping');

        // Confirmed: confirm=1, done=0, returned=0
        $sales['confirmed_count'] = $stat('confirmed', 'count');
        $sales['confirmed_total_sales'] = $stat('confirmed', 'total_sales');
        $sales['confirmed_total_sales_without_shipping'] = $stat('confirmed', 'total_sales_without_shipping');

        // Delivered: done=1
        $sales['delivered_count'] = $stat('delivered', 'count');
        $sales['delivered_total_sales'] = $stat('delivered', 'total_sales');
        $sales['delivered_total_sales_without_shipping'] = $stat('delivered', 'total_sales_without_shipping');

        // Returned: returned=1
        $sales['returned_count'] = $stat('returned', 'count');
        $sales['returned_total_sales'] = $stat('returned', 'total_sales');
        $sales['returned_total_sales_without_shipping'] = $stat('returned', 'total_sales_without_shipping'); 
        
        $adHistory->sales = $sales;
        $adHistory->save();
    }
}
