<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdsAccountHistory extends Model
{
    protected $table = 'ads_accounts_history';

    public $timestamps = false;

    protected $fillable = ['ad_account_detail_id', 'total_spent', 'date', 'sales'];

    protected $casts = [
        'date'        => 'date',
        'total_spent' => 'decimal:2',
        'sales'       => 'array',
    ];

    public function adsAccountDetail()
    {
        return $this->belongsTo(AdsAccountDetail::class, 'ad_account_detail_id');
    }

    /**
     * Normalize sales: if stored as double-encoded string (legacy), decode once to get array.
     */
    private function getSalesArray(): ?array
    {
        $sales = $this->sales;
        if (is_array($sales)) {
            return $sales;
        }
        if (is_string($sales)) {
            $decoded = json_decode($sales, true);
            return is_array($decoded) ? $decoded : null;
        }
        return null;
    }

    /**
     * Revenue from sales JSON (total_orders_sales). Returns null if sales not set.
     */
    public function getRevenueFromSales(): ?float
    {
        $sales = $this->getSalesArray();
        if ($sales === null || !isset($sales['total_orders_sales'])) {
            return null;
        }
        return (float) $sales['total_orders_sales'];
    }

    /**
     * Orders count from sales JSON (total_orders). Returns null if sales not set.
     */
    public function getOrdersCountFromSales(): ?int
    {
        $sales = $this->getSalesArray();
        if ($sales === null || !isset($sales['total_orders'])) {
            return null;
        }
        return (int) $sales['total_orders'];
    }

    /**
     * Total combined orders from sales JSON (total_combined_orders). Falls back to total_orders if not set.
     */
    public function getTotalCombinedOrdersFromSales(): int
    {
        $sales = $this->getSalesArray();
        if ($sales === null) {
            return 0;
        }
        if (isset($sales['total_combined_orders'])) {
            return (int) $sales['total_combined_orders'];
        }
        if (isset($sales['total_orders'])) {
            return (int) $sales['total_orders'];
        }
        return 0;
    }

    /**
     * Order count by status from sales JSON (Receipt Social: pending, confirmed, delivered, returned).
     * Uses getSalesArray() so double-encoded sales are decoded correctly.
     */
    public function getStatusCountsFromSales(): array
    {
        $sales = $this->getSalesArray();
        if ($sales === null) {
            return ['pending' => 0, 'confirmed' => 0, 'delivered' => 0, 'returned' => 0];
        }
        return [
            'pending' => (int) ($sales['pending_count'] ?? 0),
            'confirmed' => (int) ($sales['confirmed_count'] ?? 0),
            'delivered' => (int) ($sales['delivered_count'] ?? 0),
            'returned' => (int) ($sales['returned_count'] ?? 0),
        ];
    }

    /**
     * Status breakdown from sales JSON: [ 'pending' => revenue, 'confirmed' => ..., 'delivered' => ..., 'returned' => ... ].
     * Receipt Social: total_sales = total_cost + shipping_country_cost, total_sales_without_shipping = total_cost.
     * Returns null if sales not set.
     */
    public function getSalesBreakdownByStatus(): ?array
    {
        $sales = $this->getSalesArray();
        if ($sales === null) {
            return null;
        }
        $pending = (float) ($sales['pending_total_sales'] ?? 0);
        $confirmed = (float) ($sales['confirmed_total_sales'] ?? 0);
        $delivered = (float) ($sales['delivered_total_sales'] ?? 0);
        $returned = (float) ($sales['returned_total_sales'] ?? 0);
        return [
            'pending' => $pending,
            'confirmed' => $confirmed,
            'delivered' => $delivered,
            'returned' => $returned,
        ];
    }
}
