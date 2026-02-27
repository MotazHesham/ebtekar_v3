<?php

namespace App\Console\Commands;

use App\Models\EmployeeShift;
use App\Services\ShiftAnalyticsService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateOperationShiftMetrics extends Command
{
    protected $signature = 'shifts:calculate-operation-metrics {date?}';

    protected $description = 'Calculate and store metrics JSON for operation shifts on a given date (default: today).';

    public function handle(ShiftAnalyticsService $analyticsService): int
    {
        $dateInput = $this->argument('date');
        $date = $dateInput ? Carbon::parse($dateInput)->format('Y-m-d') : date('Y-m-d');

        $shifts = EmployeeShift::where('type', 'operation')
            ->whereDate('shift_date', $date)
            ->get();

        if ($shifts->isEmpty()) {
            $this->info("No operation shifts found for {$date}.");
            return 0;
        }

        foreach ($shifts as $shift) {
            $shift->metrics = $analyticsService->buildMetricsPayload($shift);
            $shift->save();
        }

        $this->info("Calculated metrics for {$shifts->count()} operation shifts on {$date}.");

        return 0;
    }
}

