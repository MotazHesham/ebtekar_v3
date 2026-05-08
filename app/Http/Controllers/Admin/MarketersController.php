<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\MarketerReportsExport;
use App\Models\Marketer;
use App\Models\MarketerWalletTransaction;
use App\Models\OrderMarketerAttribution;
use App\Models\ReferralVisit;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class MarketersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('marketer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $marketers = Marketer::with(['user', 'website'])->latest()->get();
        return view('admin.marketers.index', compact('marketers'));
    }

    public function create()
    {
        abort_if(Gate::denies('marketer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(__('global.pleaseSelect'), '');
        return view('admin.marketers.create', compact('websites'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('marketer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:marketers,code'],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'website_setting_id' => ['nullable', 'exists:website_settings,id'],
            'is_active' => ['nullable', 'boolean'],
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'user_phone_number' => ['nullable', 'string', 'max:255'],
            'user_password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'] ?? null,
                'phone_number' => $validated['user_phone_number'] ?? null,
                'password' => $validated['user_password'],
                'user_type' => 'marketer',
                'approved' => 1,
                'verified' => 1,
                'website_setting_id' => $validated['website_setting_id'] ?? null,
            ]);

            Marketer::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'commission_rate' => $validated['commission_rate'],
                'website_setting_id' => $validated['website_setting_id'] ?? null,
                'is_active' => $request->has('is_active'),
                'user_id' => $user->id,
            ]);
        });

        toast(__('flash.global.success_title'), 'success');
        return redirect()->route('admin.marketers.index');
    }

    public function edit(Marketer $marketer)
    {
        abort_if(Gate::denies('marketer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(__('global.pleaseSelect'), '');
        $marketer->load('user');
        return view('admin.marketers.edit', compact('marketer', 'websites'));
    }

    public function update(Request $request, Marketer $marketer)
    {
        abort_if(Gate::denies('marketer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:marketers,code,' . $marketer->id],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'website_setting_id' => ['nullable', 'exists:website_settings,id'],
            'is_active' => ['nullable', 'boolean'],
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($marketer->user_id),
            ],
            'user_phone_number' => ['nullable', 'string', 'max:255'],
            'user_password' => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request, $validated, $marketer) {
            $user = $marketer->user;
            if (!$user) {
                $user = User::create([
                    'name' => $validated['user_name'],
                    'email' => $validated['user_email'] ?? null,
                    'phone_number' => $validated['user_phone_number'] ?? null,
                    'password' => $validated['user_password'] ?? 'password123',
                    'user_type' => 'marketer',
                    'approved' => 1,
                    'verified' => 1,
                    'website_setting_id' => $validated['website_setting_id'] ?? null,
                ]);
                $marketer->user_id = $user->id;
            } else {
                $user->name = $validated['user_name'];
                $user->email = $validated['user_email'] ?? null;
                $user->phone_number = $validated['user_phone_number'] ?? null;
                $user->user_type = 'marketer';
                $user->website_setting_id = $validated['website_setting_id'] ?? null;
                if (!empty($validated['user_password'])) {
                    $user->password = $validated['user_password'];
                }
                $user->save();
            }

            $marketer->name = $validated['name'];
            $marketer->code = $validated['code'];
            $marketer->commission_rate = $validated['commission_rate'];
            $marketer->website_setting_id = $validated['website_setting_id'] ?? null;
            $marketer->is_active = $request->has('is_active');
            $marketer->save();
        });

        toast(__('flash.global.update_title'), 'success');
        return redirect()->route('admin.marketers.index');
    }

    public function show(Marketer $marketer)
    {
        abort_if(Gate::denies('marketer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $marketer->load(['user', 'website']);
        return view('admin.marketers.show', compact('marketer'));
    }

    public function destroy(Marketer $marketer)
    {
        abort_if(Gate::denies('marketer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $marketer->delete();
        return 1;
    }

    public function update_statuses(Request $request)
    {
        $marketer = Marketer::findOrFail($request->id);
        $marketer->is_active = (bool) $request->status;
        $marketer->save();
        return 1;
    }

    public function reports(Request $request)
    {
        abort_if(Gate::denies('marketer_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $marketers = Marketer::orderBy('name')->get();
        $query = $this->reportQuery($request);
        $rows = (clone $query)
            ->latest()
            ->paginate(30)
            ->appends($request->query());

        $totals = $this->reportTotals(clone $query);

        return view('admin.marketers.reports', compact(
            'rows',
            'marketers',
            'totals'
        ));
    }

    public function referralDashboard(Request $request)
    {
        abort_if(Gate::denies('marketer_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $marketers = Marketer::orderBy('name')->get();
        $visitsQuery = $this->referralVisitsQuery($request);

        $totalVisits = (clone $visitsQuery)->count();
        $uniqueVisitors = (clone $visitsQuery)->selectRaw("COUNT(DISTINCT COALESCE(NULLIF(cookie_id, ''), ip)) as aggregate")->value('aggregate') ?? 0;
        $uniqueSessions = (clone $visitsQuery)->whereNotNull('session_id')->distinct('session_id')->count('session_id');

        $ordersQuery = OrderMarketerAttribution::query()
            ->when($request->filled('marketer_id'), function ($query) use ($request) {
                $query->where('marketer_id', $request->marketer_id);
            })
            ->when($request->filled('source'), function ($query) use ($request) {
                $query->where('source', $request->source);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereHas('order', function ($orderQuery) use ($request) {
                    $orderQuery->whereDate('created_at', '>=', $request->from_date);
                });
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereHas('order', function ($orderQuery) use ($request) {
                    $orderQuery->whereDate('created_at', '<=', $request->to_date);
                });
            });

        $attributedOrders = (clone $ordersQuery)->count();
        $deliveredOrders = (clone $ordersQuery)->where('commission_status', 'approved')->count();
        $conversionRate = $totalVisits > 0 ? round(($attributedOrders / $totalVisits) * 100, 2) : 0;

        $topMarketers = (clone $visitsQuery)
            ->select('marketer_id', DB::raw('COUNT(*) as visits'))
            ->with('marketer:id,name,code')
            ->groupBy('marketer_id')
            ->orderByDesc('visits')
            ->take(10)
            ->get();

        $sourceBreakdown = (clone $visitsQuery)
            ->select('utm_source', DB::raw('COUNT(*) as visits'))
            ->groupBy('utm_source')
            ->orderByDesc('visits')
            ->take(10)
            ->get();

        $deviceBreakdown = (clone $visitsQuery)
            ->select('device', DB::raw('COUNT(*) as visits'))
            ->groupBy('device')
            ->orderByDesc('visits')
            ->get();

        $browserBreakdown = (clone $visitsQuery)
            ->select('browser', DB::raw('COUNT(*) as visits'))
            ->groupBy('browser')
            ->orderByDesc('visits')
            ->take(10)
            ->get();

        $dailyVisits = (clone $visitsQuery)
            ->selectRaw('DATE(created_at) as day, COUNT(*) as visits')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get();

        return view('admin.marketers.referral_dashboard', compact(
            'marketers',
            'totalVisits',
            'uniqueVisitors',
            'uniqueSessions',
            'attributedOrders',
            'deliveredOrders',
            'conversionRate',
            'topMarketers',
            'sourceBreakdown',
            'deviceBreakdown',
            'browserBreakdown',
            'dailyVisits'
        ));
    }

    public function reportsExcel(Request $request)
    {
        abort_if(Gate::denies('marketer_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rows = $this->reportQuery($request)->latest()->get();
        return Excel::download(new MarketerReportsExport($rows), 'marketers_report.xlsx');
    }

    public function reportsPdf(Request $request)
    {
        abort_if(Gate::denies('marketer_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rows = $this->reportQuery($request)->latest()->get();
        $totals = $this->reportTotals($this->reportQuery($request));

        return view('admin.marketers.reports_pdf', compact('rows', 'totals'));
    }

    public function payout(Request $request)
    {
        abort_if(Gate::denies('marketer_payout'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'marketer_id' => ['required', 'exists:marketers,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $marketer = Marketer::findOrFail($validated['marketer_id']);
        $amount = round((float) $validated['amount'], 2);

        $totalApprovedUnpaid = (float) OrderMarketerAttribution::query()
            ->where('marketer_id', $marketer->id)
            ->where('commission_status', 'approved')
            ->whereNull('paid_at')
            ->sum('commission_amount');

        if ($amount > $totalApprovedUnpaid) {
            return back()->withErrors(['amount' => 'Payout amount exceeds approved unpaid commission.'])->withInput();
        }

        DB::transaction(function () use ($marketer, $amount, $validated) {
            $lastBalance = (float) MarketerWalletTransaction::query()
                ->where('marketer_id', $marketer->id)
                ->latest('id')
                ->value('balance_after');

            MarketerWalletTransaction::create([
                'marketer_id' => $marketer->id,
                'type' => 'payment_debit',
                'amount' => -$amount,
                'balance_after' => round($lastBalance - $amount, 2),
                'reference_type' => 'admin_payout',
                'reference_id' => auth()->id(),
                'notes' => $validated['notes'] ?? 'Manual payout from admin panel',
                'created_by' => auth()->id(),
            ]);

            $remaining = $amount;
            $attributions = OrderMarketerAttribution::query()
                ->where('marketer_id', $marketer->id)
                ->where('commission_status', 'approved')
                ->whereNull('paid_at')
                ->orderBy('approved_at')
                ->orderBy('id')
                ->get();

            foreach ($attributions as $item) {
                if ($remaining <= 0) {
                    break;
                }

                $itemAmount = (float) $item->commission_amount;
                if ($itemAmount <= $remaining) {
                    $item->paid_at = now();
                    $item->save();
                    $remaining -= $itemAmount;
                }
            }
        });

        toast('Payout added successfully', 'success');
        return redirect()->route('admin.marketers.reports', ['marketer_id' => $marketer->id]);
    }

    public function payoutHistory(Request $request)
    {
        abort_if(Gate::denies('marketer_payout_history'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $marketers = Marketer::orderBy('name')->get();

        $transactions = MarketerWalletTransaction::with('marketer')
            ->where('type', 'payment_debit')
            ->when($request->filled('marketer_id'), function ($query) use ($request) {
                $query->where('marketer_id', $request->marketer_id);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->latest()
            ->paginate(30)
            ->appends($request->query());

        $totalPayouts = (float) MarketerWalletTransaction::where('type', 'payment_debit')
            ->when($request->filled('marketer_id'), function ($query) use ($request) {
                $query->where('marketer_id', $request->marketer_id);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->sum('amount');

        return view('admin.marketers.payout_history', compact('transactions', 'marketers', 'totalPayouts'));
    }

    private function reportQuery(Request $request)
    {
        return OrderMarketerAttribution::query()
            ->with(['marketer', 'order'])
            ->when($request->filled('marketer_id'), function ($query) use ($request) {
                $query->where('marketer_id', $request->marketer_id);
            })
            ->when($request->filled('commission_status'), function ($query) use ($request) {
                $query->where('commission_status', $request->commission_status);
            })
            ->when($request->filled('source'), function ($query) use ($request) {
                $query->where('source', $request->source);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereHas('order', function ($orderQuery) use ($request) {
                    $orderQuery->whereDate('created_at', '>=', $request->from_date);
                });
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereHas('order', function ($orderQuery) use ($request) {
                    $orderQuery->whereDate('created_at', '<=', $request->to_date);
                });
            });
    }

    private function reportTotals($totalsQuery): array
    {
        $totalOrders = (clone $totalsQuery)->count();
        $totalSales = (float) (clone $totalsQuery)->sum('commission_base');
        $totalCommission = (float) (clone $totalsQuery)->sum('commission_amount');
        $totalPaid = (float) (clone $totalsQuery)->whereNotNull('paid_at')->sum('commission_amount');
        $totalDue = $totalCommission - $totalPaid;

        return compact('totalOrders', 'totalSales', 'totalCommission', 'totalPaid', 'totalDue');
    }

    private function referralVisitsQuery(Request $request)
    {
        return ReferralVisit::query()
            ->when($request->filled('marketer_id'), function ($query) use ($request) {
                $query->where('marketer_id', $request->marketer_id);
            })
            ->when($request->filled('source'), function ($query) use ($request) {
                $query->where('utm_source', $request->source);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });
    }
}
