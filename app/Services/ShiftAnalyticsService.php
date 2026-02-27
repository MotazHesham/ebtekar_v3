<?php

namespace App\Services;

use App\Models\EmployeeShift;
use App\Models\WorkflowOperation;
use Illuminate\Support\Facades\DB;

class ShiftAnalyticsService
{
    public function getCreatorShiftMetrics(EmployeeShift $shift): array
    {
        $userId = $shift->user_id;

        $orders = DB::table('orders')
            ->where('creator_shift_id', $shift->id)
            ->selectRaw('COUNT(*) as orders_count, COALESCE(SUM(total_cost),0) as total_revenue')
            ->first();

        $receiptSocials = DB::table('receipt_socials')
            ->where('creator_shift_id', $shift->id)
            ->selectRaw('COUNT(*) as receipts_count, COALESCE(SUM(total_cost),0) as total_revenue')
            ->first();

        $receiptCompanies = DB::table('receipt_companies')
            ->where('creator_shift_id', $shift->id)
            ->selectRaw('COUNT(*) as receipts_count, COALESCE(SUM(total_cost),0) as total_revenue')
            ->first();

        return [
            'orders_created'          => (int) ($orders->orders_count ?? 0),
            'orders_revenue'         => (float) ($orders->total_revenue ?? 0),
            'receipt_socials_created' => (int) ($receiptSocials->receipts_count ?? 0),
            'receipt_socials_revenue' => (float) ($receiptSocials->total_revenue ?? 0),
            'receipt_companies_created' => (int) ($receiptCompanies->receipts_count ?? 0),
            'receipt_companies_revenue' => (float) ($receiptCompanies->total_revenue ?? 0),
        ];
    }

    public function getOperationShiftMetrics(EmployeeShift $shift): array
    {
        $operations = WorkflowOperation::where('shift_id', $shift->id)
            ->where('status', 'completed')
            ->get();

        $grouped = $operations->groupBy('stage');

        $operationsPerStage = $grouped
            ->map(function ($ops) {
                return $ops->count();
            })
            ->toArray();

        $averageDurationPerStage = $grouped
            ->map(function ($ops) {
                $totalSeconds = 0;
                $count        = 0;

                foreach ($ops as $op) {
                    if ($op->started_at && $op->ended_at) {
                        $startedAt = \Carbon\Carbon::parse($op->started_at);
                        $endedAt = \Carbon\Carbon::parse($op->ended_at);
                        $totalSeconds += $endedAt->diffInSeconds($startedAt);
                        $count++;
                    }
                }

                return $count > 0 ? $totalSeconds / $count : 0;
            })
            ->toArray();

        $totalWorkloadSeconds = 0;
        foreach ($operations as $op) {
            if ($op->started_at && $op->ended_at) {
                $totalWorkloadSeconds += \Carbon\Carbon::parse($op->ended_at)->diffInSeconds(\Carbon\Carbon::parse($op->started_at));
            }
        }

        return [
            'operations_completed'      => $operations->count(),
            'operations_per_stage'      => $operationsPerStage,
            'avg_duration_per_stage_s'  => $averageDurationPerStage,
            'total_workload_seconds'    => $totalWorkloadSeconds,
        ];
    }

    public function getSalesMetricsForShift(EmployeeShift $shift): array
    {
        return $this->getSalesMetricsForShiftIds([$shift->id]);
    }

    public function getSalesMetricsForShiftIds($shiftIds): array
    {
        $ids = collect($shiftIds)->filter()->values();

        if ($ids->isEmpty()) {
            return [
                'receipts_count' => 0,
                'products_count' => 0,
                'total_revenue'  => 0.0,
            ];
        }

        $receiptSocials = DB::table('receipt_socials')
            ->whereIn('creator_shift_id', $ids)
            ->selectRaw('COUNT(*) as receipts_count, COALESCE(SUM(total_cost),0) as total_revenue')
            ->first();

        $receiptCompanies = DB::table('receipt_companies')
            ->whereIn('creator_shift_id', $ids)
            ->selectRaw('COUNT(*) as receipts_count, COALESCE(SUM(total_cost),0) as total_revenue')
            ->first();

        $productsCount = DB::table('receipt_social_receipt_social_product')
            ->join('receipt_socials', 'receipt_social_receipt_social_product.receipt_social_id', '=', 'receipt_socials.id')
            ->whereIn('receipt_socials.creator_shift_id', $ids)
            ->sum('quantity');

        return [
            'receipts_count' => (int) (($receiptSocials->receipts_count ?? 0) + ($receiptCompanies->receipts_count ?? 0)),
            'products_count' => (int) $productsCount,
            'total_revenue'  => (float) (($receiptSocials->total_revenue ?? 0) + ($receiptCompanies->total_revenue ?? 0)),
        ];
    }

    public function getProductsBreakdownForShift(EmployeeShift $shift): array
    {
        return $this->getProductsBreakdownForShiftIds([$shift->id]);
    }

    public function getProductsBreakdownForShiftIds($shiftIds): array
    {
        $ids = collect($shiftIds)->filter()->values();

        if ($ids->isEmpty()) {
            return [];
        }

        return DB::table('receipt_social_receipt_social_product')
            ->join('receipt_socials', 'receipt_social_receipt_social_product.receipt_social_id', '=', 'receipt_socials.id')
            ->whereIn('receipt_socials.creator_shift_id', $ids)
            ->selectRaw('receipt_social_receipt_social_product.title as product_name, SUM(receipt_social_receipt_social_product.quantity) as total_quantity, COALESCE(SUM(receipt_social_receipt_social_product.total_cost),0) as total_revenue, MAX(receipt_social_receipt_social_product.photos) as photos_json')
            ->groupBy('receipt_social_receipt_social_product.title')
            ->orderByDesc('total_quantity')
            ->get()
            ->map(function ($row) {
                $photoPath = null;
                if (! empty($row->photos_json)) {
                    $decoded = json_decode($row->photos_json, true);
                    if (is_array($decoded) && ! empty($decoded)) {
                        if (isset($decoded[0]['photo'])) {
                            $photoPath = $decoded[0]['photo'];
                        } elseif (is_string($decoded[0])) {
                            $photoPath = $decoded[0];
                        }
                    }
                }

                return [
                    'product_name'  => $row->product_name,
                    'quantity'      => (int) $row->total_quantity,
                    'total_revenue' => (float) $row->total_revenue,
                    'photo'         => $photoPath,
                ];
            })
            ->toArray();
    }

    public function buildMetricsPayload(EmployeeShift $shift): array
    {
        $salesMetrics = $this->getSalesMetricsForShift($shift);

        $creatorMetrics = null;
        if ($shift->type === 'creator') {
            $creatorMetrics = $this->getCreatorShiftMetrics($shift);
        }

        $operationMetrics = $this->getOperationShiftMetrics($shift);

        return [
            'sales'      => $salesMetrics,
            'creator'    => $creatorMetrics,
            'operations' => $operationMetrics,
            'calculated_at' => now()->toIso8601String(),
        ];
    }
}

