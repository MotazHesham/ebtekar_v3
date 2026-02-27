<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeShift;
use App\Models\User;
use App\Services\ShiftAnalyticsService;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ShiftController extends Controller
{
    public function index(Request $request, ShiftAnalyticsService $analyticsService)
    {
        if (! Gate::allows('shift_access')) {
            abort(403, 'Unauthorized action.');
        }
        $user = Auth::user();

        $employees = User::whereIn('user_type', ['staff', 'admin'])->get();
        $shiftTypes = EmployeeShift::SHIFT_TYPES;

        $selectedType = $request->input('type');
        if (! $selectedType) {
            $selectedType = array_keys($shiftTypes)[0] ?? 'creator';
        }

        $employeeId    = $request->input('employee_id');
        $fromDateInput = $request->input('from_date');
        $toDateInput   = $request->input('to_date');

        $shiftQuery = EmployeeShift::with('user')
            ->where('type', $selectedType);

        if ($user->is_admin) {
            if ($employeeId) {
                $shiftQuery->where('user_id', $employeeId);
            }
        } else {
            $shiftQuery->where('user_id', $user->id);
        }

        if ($fromDateInput) {
            $fromDate = Carbon::createFromFormat(config('panel.date_format'), $fromDateInput)->format('Y-m-d');
            $shiftQuery->whereDate('shift_date', '>=', $fromDate);
        }

        if ($toDateInput) {
            $toDate = Carbon::createFromFormat(config('panel.date_format'), $toDateInput)->format('Y-m-d');
            $shiftQuery->whereDate('shift_date', '<=', $toDate);
        }

        $metricsBaseQuery = clone $shiftQuery;

        $shifts = $shiftQuery
            ->orderByDesc('shift_date')
            ->orderByDesc('started_at')
            ->paginate(20);

        $perShiftMetrics = [];
        $globalMetrics   = null;

        if ($user->is_admin) {
            // Use pre-calculated JSON metrics on the shift itself
            foreach ($shifts as $shift) {
                if (is_array($shift->metrics) && isset($shift->metrics['sales'])) {
                    $perShiftMetrics[$shift->id] = $shift->metrics['sales'];
                } else {
                    $perShiftMetrics[$shift->id] = null;
                }
            }

            // Build global metrics by summing the stored JSON for all filtered shifts
            $allForGlobal = $metricsBaseQuery->get(['id', 'metrics']);

            $totalReceipts = 0;
            $totalProducts = 0;
            $totalRevenue  = 0.0;

            foreach ($allForGlobal as $shift) {
                if (! is_array($shift->metrics) || ! isset($shift->metrics['sales'])) {
                    continue;
                }

                $sales = $shift->metrics['sales'];

                $totalReceipts += (int) ($sales['receipts_count'] ?? 0);
                $totalProducts += (int) ($sales['products_count'] ?? 0);
                $totalRevenue  += (float) ($sales['total_revenue'] ?? 0);
            }

            $globalMetrics = [
                'receipts_count' => $totalReceipts,
                'products_count' => $totalProducts,
                'total_revenue'  => $totalRevenue,
            ];
        }

        return view('admin.shifts.index', [
            'shifts'              => $shifts,
            'employees'           => $employees,
            'shiftTypes'          => $shiftTypes,
            'selectedType'        => $selectedType,
            'selectedEmployeeId'  => $employeeId,
            'fromDate'            => $fromDateInput,
            'toDate'              => $toDateInput,
            'perShiftMetrics'     => $perShiftMetrics,
            'globalMetrics'       => $globalMetrics,
            'isAdmin'             => $user->is_admin,
        ]);
    }

    public function startCreator(ShiftService $shiftService)
    {
        $user = Auth::user();

        $existing = $shiftService->getOpenCreatorShift($user);
        if ($existing) {
            return redirect()
                ->route('admin.shifts.index')
                ->with('status', __('You already have an open creator shift.'));
        }

        $shiftService->openCreatorShift($user);

        return redirect()->back();
    }

    public function endCreator(ShiftService $shiftService, ShiftAnalyticsService $analyticsService)
    {
        $user = Auth::user();
        $existing = $shiftService->getOpenCreatorShift($user);

        if (! $existing) {
            return redirect()
                ->route('admin.shifts.index')
                ->with('status', __('You do not have an open creator shift.'));
        }

        $closedShift = $shiftService->closeCreatorShift($existing);

        $closedShift->metrics = $analyticsService->buildMetricsPayload($closedShift);
        $closedShift->save();

        return redirect()
            ->route('admin.shifts.index')
            ->with('status', __('Creator shift ended successfully.'));
    }

    public function metrics(Request $request, ShiftAnalyticsService $analyticsService)
    {
        $shiftId = $request->input('id');
        $shift   = EmployeeShift::with('user')->findOrFail($shiftId);

        $metrics = is_array($shift->metrics) ? $shift->metrics : null;

        if (! $metrics) {
            return response()->json(['html' => '']);
        }

        if ($shift->type === 'creator') {
            $metrics['sales']['products_breakdown'] = $analyticsService->getProductsBreakdownForShift($shift);
            $view = 'admin.shifts.partials.metrics_creator_modal';
        } else {
            $view = 'admin.shifts.partials.metrics_operation_modal';
        }

        $html = view($view, [
            'shift'   => $shift,
            'metrics' => $metrics,
        ])->render();

        return response()->json(['html' => $html]);
    }
}

