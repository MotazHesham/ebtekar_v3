<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdsAccount;
use App\Models\AdsAccountDetail;
use App\Models\AdsAccountHistory;
use App\Models\AdsPaymentRequest;
use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdsAccountsController extends Controller
{
    /**
     * Ads accounts index: list accounts (with optional search/date), attach revenue/orders,
     * aggregate stats + ROAS chart, and optionally selected account's details tree (paginated).
     */
    public function index(Request $request)
    {
        $accounts = collect();
        $selectedAccount = null;
        $accountDetails = collect();
        $flatAccountDetails = collect();
        $totalSpent = 0;
        $totalRevenue = 0;
        $overallRoas = 0;
        $chartData = collect();
        $revenueBreakdown = [
            'pending' => 0,
            'confirmed' => 0,
            'delivered' => 0,
            'returned' => 0,
        ];
        $roasBreakdown = [
            'pending' => 0,
            'confirmed' => 0,
            'delivered' => 0,
            'returned' => 0,
        ];

        if (!Schema::hasTable('ads_accounts')) {
            return view('admin.ads.accounts', compact('accounts', 'selectedAccount', 'accountDetails', 'flatAccountDetails', 'totalSpent', 'totalRevenue', 'overallRoas', 'chartData', 'revenueBreakdown', 'roasBreakdown'));
        }

        $query = $this->buildAccountsIndexQuery($request);
        $perPage = $request->query('per_page', 15);
        $accounts = $query->paginate($perPage)->withQueryString();

        [$startDateForRevenue, $endDateForRevenue] = $this->parseDateRangeFromRequest($request->query('date_range'));
        $this->attachAccountRevenueAndOrdersFromSales($accounts, $startDateForRevenue, $endDateForRevenue);

        $accountId = $request->query('account_id') ? (int) $request->query('account_id') : null;
        [$startDate, $endDate] = $this->parseDateRangeFromRequest($request->query('date_range'));

        $stats = $this->getAggregateStatsAndChartData($accountId, $startDate, $endDate);
        $totalSpent = $stats['totalSpent'];
        $totalRevenue = $stats['totalRevenue'];
        $overallRoas = $stats['overallRoas'];
        $chartData = $stats['chartData'];
        $revenueBreakdown = $stats['revenueBreakdown'];
        $roasBreakdown = $stats['roasBreakdown'];

        if ($accountId) {
            [$select, $groupBy] = $this->getAccountsSelectAndGroupBy();
            $selectedAccount = AdsAccount::query()->select($select)->find($accountId);
            if ($selectedAccount && Schema::hasTable('ads_accounts_details')) {
                $flatAccountDetails = $this->getFlatAccountDetailsPaginated($request, $selectedAccount);
            }
        }
        
        return view('admin.ads.accounts', compact('accounts', 'selectedAccount', 'accountDetails', 'flatAccountDetails', 'totalSpent', 'totalRevenue', 'overallRoas', 'chartData', 'revenueBreakdown', 'roasBreakdown'));
    }

    /**
     * Parse "d-m-Y to d-m-Y" or generic date string into [startOfDay, endOfDay] or [null, null].
     */
    private function parseDateRangeFromRequest(?string $dateRange): array
    {
        if (empty($dateRange)) {
            return [null, null];
        }
        $dates = explode(' to ', $dateRange);
        if (count($dates) !== 2) {
            return [null, null];
        }
        try {
            $start = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[0]))->startOfDay();
            $end = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[1]))->endOfDay();
            return [$start, $end];
        } catch (\Exception $e) {
            try {
                $start = \Carbon\Carbon::parse(trim($dates[0]))->startOfDay();
                $end = \Carbon\Carbon::parse(trim($dates[1]))->endOfDay();
                return [$start, $end];
            } catch (\Exception $e2) {
                return [null, null];
            }
        }
    }

    /**
     * Return select and groupBy column lists for ads_accounts (id, name, balance, created_at, updated_at if present).
     */
    private function getAccountsSelectAndGroupBy(): array
    {
        $select = ['ads_accounts.id', 'ads_accounts.name'];
        $groupBy = ['ads_accounts.id', 'ads_accounts.name'];
        if (Schema::hasColumn('ads_accounts', 'balance')) {
            $select[] = 'ads_accounts.balance';
            $groupBy[] = 'ads_accounts.balance';
        }
        if (Schema::hasColumn('ads_accounts', 'created_at')) {
            $select[] = 'ads_accounts.created_at';
            $groupBy[] = 'ads_accounts.created_at';
        }
        if (Schema::hasColumn('ads_accounts', 'updated_at')) {
            $select[] = 'ads_accounts.updated_at';
            $groupBy[] = 'ads_accounts.updated_at';
        }
        return [$select, $groupBy];
    }

    /**
     * Build the accounts index query: select columns, search by name, order by created_at,
     * optional join to details/history and date filter for total_spent_sum.
     */
    private function buildAccountsIndexQuery(Request $request): Builder
    {
        [$select, $groupBy] = $this->getAccountsSelectAndGroupBy();
        $query = AdsAccount::query()->select($select);

        $search = $request->query('search');
        if (!empty($search)) {
            $query->where('ads_accounts.name', 'like', '%' . $search . '%');
        }
        $query->orderByDesc('ads_accounts.created_at');

        [$startDate, $endDate] = $this->parseDateRangeFromRequest($request->query('date_range'));
        if (Schema::hasTable('ads_accounts_history') && Schema::hasTable('ads_accounts_details')) {
            $query
                ->leftJoin('ads_accounts_details as aad', 'aad.ad_account_id', '=', 'ads_accounts.id')
                ->leftJoin('ads_accounts_history as aah', 'aah.ad_account_detail_id', '=', 'aad.id');
            if ($startDate && $endDate) {
                $query->whereBetween('aah.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $query->addSelect([DB::raw('COALESCE(SUM(aah.total_spent), 0) as total_spent_sum')])
                ->groupBy($groupBy);
        }

        return $query;
    }

    /**
     * Aggregate total spent, total revenue, overall ROAS, and last-7-days ROAS chart data.
     * If $accountId is set, stats and chart are for that account only; otherwise for all accounts.
     */
    private function getAggregateStatsAndChartData(?int $accountId, $startDate, $endDate): array
    {
        $totalSpent = 0;
        $totalRevenue = 0;
        $overallRoas = 0;
        $chartData = collect();
        $revenueBreakdown = [
            'pending' => 0,
            'confirmed' => 0,
            'delivered' => 0,
            'returned' => 0,
        ];
        $roasBreakdown = [
            'pending' => 0,
            'confirmed' => 0,
            'delivered' => 0,
            'returned' => 0,
        ];

        if ($accountId) {
            $spentQuery = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $accountId);
            if ($startDate && $endDate) {
                $spentQuery->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $totalSpent = $spentQuery->sum('ads_accounts_history.total_spent');

            $accountHistoryIds = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $accountId)
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                })
                ->pluck('ads_accounts_history.id')
                ->toArray();

            $accountHistoryWithSales = AdsAccountHistory::query()
                ->whereIn('id', $accountHistoryIds)
                ->get(['id', 'sales']);
            $totalRevenue = (float) $accountHistoryWithSales->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
            
            // Calculate revenue breakdown by status (Receipt Social: pending, confirmed, delivered, returned)
            foreach ($accountHistoryWithSales as $h) {
                $breakdown = $h->getSalesBreakdownByStatus();
                if ($breakdown) {
                    $revenueBreakdown['pending'] += $breakdown['pending'];
                    $revenueBreakdown['confirmed'] += $breakdown['confirmed'];
                    $revenueBreakdown['delivered'] += $breakdown['delivered'];
                    $revenueBreakdown['returned'] += $breakdown['returned'];
                }
            }
            
            $overallRoas = $totalSpent > 0 ? ($totalRevenue / $totalSpent) : 0;
            
            // Calculate ROAS breakdown by status (status_revenue / total_spent)
            if ($totalSpent > 0) {
                $roasBreakdown['pending'] = $revenueBreakdown['pending'] / $totalSpent;
                $roasBreakdown['confirmed'] = $revenueBreakdown['confirmed'] / $totalSpent;
                $roasBreakdown['delivered'] = $revenueBreakdown['delivered'] / $totalSpent;
                $roasBreakdown['returned'] = $revenueBreakdown['returned'] / $totalSpent;
            }

            $chartQuery = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $accountId);
            if ($startDate && $endDate) {
                $chartQuery->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $chartData = $this->buildRoasChartData($chartQuery, $startDate, $endDate);
        } else {
            $spentQuery = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id');
            if ($startDate && $endDate) {
                $spentQuery->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $totalSpent = $spentQuery->sum('ads_accounts_history.total_spent');

            $allHistoryInRange = AdsAccountHistory::query();
            if ($startDate && $endDate) {
                $allHistoryInRange->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $allHistoryInRange = $allHistoryInRange->get(['id', 'sales']);
            $totalRevenue = (float) $allHistoryInRange->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
            
            // Calculate revenue breakdown by status (Receipt Social: pending, confirmed, delivered, returned)
            foreach ($allHistoryInRange as $h) {
                $breakdown = $h->getSalesBreakdownByStatus();
                if ($breakdown) {
                    $revenueBreakdown['pending'] += $breakdown['pending'];
                    $revenueBreakdown['confirmed'] += $breakdown['confirmed'];
                    $revenueBreakdown['delivered'] += $breakdown['delivered'];
                    $revenueBreakdown['returned'] += $breakdown['returned'];
                }
            }
            
            $overallRoas = $totalSpent > 0 ? ($totalRevenue / $totalSpent) : 0;
            
            // Calculate ROAS breakdown by status (status_revenue / total_spent)
            if ($totalSpent > 0) {
                $roasBreakdown['pending'] = $revenueBreakdown['pending'] / $totalSpent;
                $roasBreakdown['confirmed'] = $revenueBreakdown['confirmed'] / $totalSpent;
                $roasBreakdown['delivered'] = $revenueBreakdown['delivered'] / $totalSpent;
                $roasBreakdown['returned'] = $revenueBreakdown['returned'] / $totalSpent;
            }

            $chartQuery = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id');
            if ($startDate && $endDate) {
                $chartQuery->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $chartData = $this->buildRoasChartData($chartQuery, $startDate, $endDate);
        }

        return [
            'totalSpent' => $totalSpent,
            'totalRevenue' => $totalRevenue,
            'overallRoas' => $overallRoas,
            'chartData' => $chartData,
            'revenueBreakdown' => $revenueBreakdown,
            'roasBreakdown' => $roasBreakdown,
        ];
    }

    /**
     * Build ROAS trend chart data.
     * - When a custom date range is set: use every day in the range (x-axis from start to end), with 0 for days that have no data.
     * - When no date range: use the last 7 dates that have history data.
     */
    private function buildRoasChartData(Builder $chartQuery, $startDate, $endDate): Collection
    {
        $useFullRange = $startDate && $endDate;

        if ($useFullRange) {
            // Build list of all dates in the selected range (so x-axis starts from user's start date)
            $current = \Carbon\Carbon::parse($startDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->startOfDay();
            $maxPoints = 60; // cap for very long ranges
            $allDatesInRange = collect();
            while ($current->lte($end) && $allDatesInRange->count() < $maxPoints) {
                $allDatesInRange->push($current->copy());
                $current->addDay();
            }
            if ($allDatesInRange->isEmpty()) {
                return collect();
            }
            $datesToUse = $allDatesInRange->map(fn ($d) => $d->format('Y-m-d'));
        } else {
            // No date filter: last 7 dates that have data
            $recentHistoryDates = (clone $chartQuery)
                ->select('ads_accounts_history.date', DB::raw('SUM(ads_accounts_history.total_spent) as total_spent'))
                ->groupBy('ads_accounts_history.date')
                ->orderByDesc('ads_accounts_history.date')
                ->take(7)
                ->get(['date', 'total_spent'])
                ->reverse();
            if ($recentHistoryDates->isEmpty()) {
                return collect();
            }
            $datesToUse = $recentHistoryDates->pluck('date')->map(fn ($d) => $d ? $d->format('Y-m-d') : '')->filter();
        }

        $datesArray = $datesToUse->values()->toArray();

        // Get spent per date (only for dates we need)
        $spentPerDate = (clone $chartQuery)
            ->select('ads_accounts_history.date', DB::raw('SUM(ads_accounts_history.total_spent) as total_spent'))
            ->groupBy('ads_accounts_history.date')
            ->when(!empty($datesArray), fn ($q) => $q->whereIn('ads_accounts_history.date', $datesArray))
            ->get()
            ->keyBy(fn ($item) => $item->date ? $item->date->format('Y-m-d') : '');

        $dateHistoryMapQuery = (clone $chartQuery)
            ->select('ads_accounts_history.id', 'ads_accounts_history.date', 'ads_accounts_history.sales')
            ->when(!empty($datesArray), fn ($q) => $q->whereIn('ads_accounts_history.date', $datesArray));
        $dateHistoryMap = $dateHistoryMapQuery->get()
            ->groupBy(fn ($item) => $item->date ? $item->date->format('Y-m-d') : '');

        $formatDateForLabel = fn ($dateStr) => \Carbon\Carbon::parse($dateStr)->format('M d');

        if ($useFullRange) {
            return $allDatesInRange->map(function ($dateObj) use ($spentPerDate, $dateHistoryMap, $formatDateForLabel) {
                $dateKey = $dateObj->format('Y-m-d');
                $spentRow = $spentPerDate->get($dateKey);
                $spent = (float) ($spentRow->total_spent ?? 0);
                $dateHistoryItems = $dateHistoryMap->get($dateKey) ?? collect();
                $revenue = (float) $dateHistoryItems->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
                $roas = $spent > 0 ? ($revenue / $spent) : 0;
                return [
                    'date' => $formatDateForLabel($dateKey),
                    'roas' => $roas,
                ];
            })->values();
        }

        return $recentHistoryDates->map(function ($item) use ($dateHistoryMap, $formatDateForLabel) {
            $date = $item->date;
            $spent = (float) ($item->total_spent ?? 0);
            $dateKey = $date ? $date->format('Y-m-d') : '';
            $dateHistoryItems = $dateHistoryMap->get($dateKey) ?? collect();
            $revenue = (float) $dateHistoryItems->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
            $roas = $spent > 0 ? ($revenue / $spent) : 0;
            return [
                'date' => $date ? $formatDateForLabel($dateKey) : '',
                'roas' => $roas,
            ];
        })->values();
    }

    /**
     * Load selected account's details (with optional search), attach spend/revenue/orders/ROAS per detail
     * (including descendants), flatten tree, and return paginated flat list.
     */
    private function getFlatAccountDetailsPaginated(Request $request, AdsAccount $selectedAccount): LengthAwarePaginator
    {
        [$startDate, $endDate] = $this->parseDateRangeFromRequest($request->query('date_range'));

        $allAccountDetailsQuery = AdsAccountDetail::query()
            ->with('parent')
            ->where('ad_account_id', $selectedAccount->id);

        $searchDetails = $request->query('search');
        if (!empty($searchDetails)) {
            $allAccountDetailsQuery->where(function ($q) use ($searchDetails) {
                $q->where('name', 'like', '%' . $searchDetails . '%')
                    ->orWhere('utm_key', 'like', '%' . $searchDetails . '%');
            });
        }
        $allAccountDetails = $allAccountDetailsQuery->orderBy('id')->get(['id', 'parent_id', 'name', 'utm_key', 'type']);

        if ($allAccountDetails->isNotEmpty() && Schema::hasTable('ads_accounts_history')) {
            $detailIds = $allAccountDetails->pluck('id')->toArray();
            $allHistoryForDetails = AdsAccountHistory::query()
                ->select('id', 'ad_account_detail_id', 'total_spent', 'sales')
                ->whereIn('ad_account_detail_id', $detailIds)
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                })
                ->get();

            foreach ($allAccountDetails as $detail) {
                $descendantIds = $this->getDetailIdsWithDescendants($selectedAccount->id, $detail->id);
                $detailTotalSpent = 0;
                $revenue = 0;
                $ordersCount = 0;

                foreach ($allHistoryForDetails as $historyRow) {
                    if (!in_array((int) $historyRow->ad_account_detail_id, $descendantIds, true)) {
                        continue;
                    }
                    $detailTotalSpent += (float) ($historyRow->total_spent ?? 0);
                    $revenue += (float) ($historyRow->getRevenueFromSales() ?? 0);
                    $ordersCount += (int) ($historyRow->getOrdersCountFromSales() ?? 0);
                }

                $detail->total_spent = $detailTotalSpent;
                $detail->revenue = $revenue;
                $detail->orders_count = $ordersCount;
                $detail->roas = $detail->total_spent > 0 ? ($revenue / $detail->total_spent) : 0;
            }
        } else {
            $allAccountDetails->each(function ($detail) {
                $detail->total_spent = 0;
                $detail->revenue = 0;
                $detail->orders_count = 0;
                $detail->roas = 0;
            });
        }

        $allAccountDetails->load('parent');
        $byParent = $allAccountDetails->groupBy('parent_id');
        $flat = collect();
        $walk = function ($parentId, $level) use (&$walk, $byParent, &$flat) {
            foreach (($byParent[$parentId] ?? collect()) as $detail) {
                $flat->push([
                    'detail' => $detail,
                    'level' => $level,
                    'hasChildren' => isset($byParent[$detail->id]) && $byParent[$detail->id]->count() > 0,
                ]);
                $walk($detail->id, $level + 1);
            }
        };
        $walk(null, 0);

        $perPageDetails = $request->query('per_page_details', 15);
        $currentPage = max(1, (int) $request->query('page_details', 1));

        // Paginate by root nodes so each page contains full trees (parent + all descendants).
        // Otherwise children can be on another page and expand/collapse would show nothing.
        $rootIndices = $flat->keys()->filter(function ($idx) use ($flat) {
            return ($flat->get($idx)['level'] ?? 0) === 0;
        })->values();
        $totalRoots = $rootIndices->count();
        $rootStart = ($currentPage - 1) * $perPageDetails;
        $rootEnd = min($rootStart + $perPageDetails, $totalRoots);
        $sliceStart = $rootStart < $totalRoots ? $rootIndices->get($rootStart) : 0;
        $sliceEnd = $rootEnd < $totalRoots ? $rootIndices->get($rootEnd) : $flat->count();
        $length = max(0, $sliceEnd - $sliceStart);
        $items = $flat->slice($sliceStart, $length)->values();

        return new LengthAwarePaginator(
            $items,
            $totalRoots,
            $perPageDetails,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page_details',
                'query' => $request->except('page_details'),
            ]
        );
    }

    /**
     * Attach revenue_sum, orders_count and status counts to each account from ads_accounts_history.sales JSON.
     */
    private function attachAccountRevenueAndOrdersFromSales($accounts, $startDate = null, $endDate = null): void
    {
        if ($accounts->isEmpty() || !Schema::hasTable('ads_accounts_history')) {
            $accounts->each(function ($account) {
                $account->revenue_sum = 0;
                $account->orders_count = 0;
                $account->status_pending = 0;
                $account->status_confirmed = 0;
                $account->status_delivered = 0;
                $account->status_returned = 0;
            });
            return;
        }

        $accountIds = $accounts->pluck('id')->toArray();
        $historyIdsByAccount = [];

        if (Schema::hasTable('ads_accounts_details')) {
            $historyQuery = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->whereIn('ads_accounts_details.ad_account_id', $accountIds);
            if ($startDate && $endDate) {
                $historyQuery->whereBetween('ads_accounts_history.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            }
            $historyRecords = $historyQuery
                ->select('ads_accounts_history.id', 'ads_accounts_history.sales', 'ads_accounts_details.ad_account_id')
                ->get();
            foreach ($historyRecords as $history) {
                if (!isset($historyIdsByAccount[$history->ad_account_id])) {
                    $historyIdsByAccount[$history->ad_account_id] = [];
                }
                $historyIdsByAccount[$history->ad_account_id][] = $history;
            }
        }

        foreach ($accounts as $account) {
            $historyItems = $historyIdsByAccount[$account->id] ?? [];
            $revenue = 0;
            $ordersCount = 0;
            $pendingCount = 0;
            $confirmedCount = 0;
            $deliveredCount = 0;
            $returnedCount = 0;
            foreach ($historyItems as $historyItem) {
                $revenue += (float) ($historyItem->getRevenueFromSales() ?? 0);
                $ordersCount += (int) ($historyItem->getOrdersCountFromSales() ?? 0);
                $counts = $historyItem->getStatusCountsFromSales();
                $pendingCount += $counts['pending'];
                $confirmedCount += $counts['confirmed'];
                $deliveredCount += $counts['delivered'];
                $returnedCount += $counts['returned'];
            }
            $account->revenue_sum = (float) $revenue;
            $account->orders_count = (int) $ordersCount;
            $account->status_pending = $pendingCount;
            $account->status_confirmed = $confirmedCount;
            $account->status_delivered = $deliveredCount;
            $account->status_returned = $returnedCount;
        }
    }

    public function create()
    {
        return view('admin.ads.accounts_create');
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads accounts table is not migrated yet.'));
        }

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'balance' => ['nullable', 'numeric'],
        ]);

        AdsAccount::create([
            'name'    => $validated['name'],
            'balance' => $validated['balance'] ?? 0,
        ]);

        return redirect()->route('admin.ads.accounts.index')->with('success', trans('Account created successfully'));
    }

    private function getDetailIdsWithDescendants(int $accountId, int $detailId): array
    {
        $detail = AdsAccountDetail::query()
            ->where('ad_account_id', $accountId)
            ->where('id', $detailId)
            ->first();
        if (!$detail) {
            return [];
        }
        $ids = [$detail->id];
        $children = AdsAccountDetail::query()
            ->where('ad_account_id', $accountId)
            ->where('parent_id', $detailId)
            ->pluck('id')
            ->toArray();
        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getDetailIdsWithDescendants($accountId, $childId));
        }
        return $ids;
    }

    public function destroy($account)
    {
        if (!Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads accounts table is not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        DB::beginTransaction();
        try {
            $detailIds = AdsAccountDetail::query()
                ->where('ad_account_id', $selectedAccount->id)
                ->pluck('id')
                ->toArray();

            if (!empty($detailIds) && Schema::hasTable('ads_accounts_history')) {
                AdsAccountHistory::query()->whereIn('ad_account_detail_id', $detailIds)->delete();
            }
            AdsAccountDetail::query()->where('ad_account_id', $selectedAccount->id)->delete();
            if (Schema::hasTable('ads_payments_requests')) {
                AdsPaymentRequest::query()->where('ad_account_id', $selectedAccount->id)->delete();
            }
            $selectedAccount->delete();
            DB::commit();
            return redirect()->route('admin.ads.accounts.index')->with('success', trans('Account deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', trans('An error occurred while deleting the account.'));
        }
    }

    public function roasBreakdown(Request $request, $account)
    {
        if (!Schema::hasTable('ads_accounts')) {
            return response()->json(['error' => trans('Ads accounts table is not migrated yet.')], 400);
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return response()->json(['error' => trans('Account not found')], 404);
        }

        $detailId = $request->query('detail_id');
        $detailIdsFilter = null;
        if ($detailId && is_numeric($detailId)) {
            $detailIdsFilter = $this->getDetailIdsWithDescendants($selectedAccount->id, (int) $detailId);
            if (empty($detailIdsFilter)) {
                return response()->json(['error' => trans('Detail not found')], 404);
            }
        }

        // Get history IDs for this account (optionally scoped to a detail and its children)
        $historyIds = [];
        if (Schema::hasTable('ads_accounts_details') && Schema::hasTable('ads_accounts_history')) {
            $query = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $selectedAccount->id);
            if ($detailIdsFilter !== null) {
                $query->whereIn('ads_accounts_details.id', $detailIdsFilter);
            }
            $historyIds = $query->pluck('ads_accounts_history.id')->toArray();
        }

        $attributedRevenue = 0;
        $totalSpent = 0;
        $totalCombinedOrders = 0;
        $roasByStatus = [];
        $topProducts = [];

        if (!empty($historyIds)) {
            $totalSpent = AdsAccountHistory::query()
                ->whereIn('id', $historyIds)
                ->sum('total_spent');

            $historyWithSales = AdsAccountHistory::query()
                ->whereIn('id', $historyIds)
                ->get(['id', 'sales']);

            // Revenue, orders and ROAS breakdown from sales JSON only
            $attributedRevenue = (float) $historyWithSales->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
            $totalCombinedOrders = (int) $historyWithSales->sum(fn ($h) => $h->getTotalCombinedOrdersFromSales());
            $totalRevenueWithoutShipping = 0;
            $pendingRevenue = $confirmedRevenue = $deliveredRevenue = $returnedRevenue = 0;
            $pendingRevenueWithoutShipping = $confirmedRevenueWithoutShipping = $deliveredRevenueWithoutShipping = $returnedRevenueWithoutShipping = 0;
            $pendingCount = $confirmedCount = $deliveredCount = $returnedCount = 0;

            foreach ($historyWithSales as $h) {
                $breakdown = $h->getSalesBreakdownByStatus();
                if ($breakdown) {
                    $pendingRevenue += $breakdown['pending'];
                    $confirmedRevenue += $breakdown['confirmed'];
                    $deliveredRevenue += $breakdown['delivered'];
                    $returnedRevenue += $breakdown['returned'];
                }

                $statusCounts = $h->getStatusCountsFromSales();
                $pendingCount += $statusCounts['pending'];
                $confirmedCount += $statusCounts['confirmed'];
                $deliveredCount += $statusCounts['delivered'];
                $returnedCount += $statusCounts['returned'];

                // Access sales directly (it's cast as array in the model)
                $sales = $h->sales;
                if (is_array($sales)) {
                    $totalRevenueWithoutShipping += (float) ($sales['total_orders_sales_without_shipping'] ?? 0);
                    $pendingRevenueWithoutShipping += (float) ($sales['pending_total_sales_without_shipping'] ?? 0);
                    $confirmedRevenueWithoutShipping += (float) ($sales['confirmed_total_sales_without_shipping'] ?? 0);
                    $deliveredRevenueWithoutShipping += (float) ($sales['delivered_total_sales_without_shipping'] ?? 0);
                    $returnedRevenueWithoutShipping += (float) ($sales['returned_total_sales_without_shipping'] ?? 0);
                } elseif (is_string($sales)) {
                    $decoded = json_decode($sales, true);
                    if (is_array($decoded)) {
                        $totalRevenueWithoutShipping += (float) ($decoded['total_orders_sales_without_shipping'] ?? 0);
                        $pendingRevenueWithoutShipping += (float) ($decoded['pending_total_sales_without_shipping'] ?? 0);
                        $confirmedRevenueWithoutShipping += (float) ($decoded['confirmed_total_sales_without_shipping'] ?? 0);
                        $deliveredRevenueWithoutShipping += (float) ($decoded['delivered_total_sales_without_shipping'] ?? 0);
                        $returnedRevenueWithoutShipping += (float) ($decoded['returned_total_sales_without_shipping'] ?? 0);
                    }
                }
            }
            // ROAS per status (Receipt Social: pending, confirmed, delivered, returned)
            $statusRevenues = [
                [
                    'key' => 'pending',
                    'label' => 'Pending',
                    'revenue' => $pendingRevenue,
                    'revenue_without_shipping' => $pendingRevenueWithoutShipping,
                    'orders_count' => $pendingCount
                ],
                [
                    'key' => 'confirmed',
                    'label' => 'Confirmed',
                    'revenue' => $confirmedRevenue,
                    'revenue_without_shipping' => $confirmedRevenueWithoutShipping,
                    'orders_count' => $confirmedCount
                ],
                [
                    'key' => 'delivered',
                    'label' => 'Delivered',
                    'revenue' => $deliveredRevenue,
                    'revenue_without_shipping' => $deliveredRevenueWithoutShipping,
                    'orders_count' => $deliveredCount
                ],
                [
                    'key' => 'returned',
                    'label' => 'Returned',
                    'revenue' => $returnedRevenue,
                    'revenue_without_shipping' => $returnedRevenueWithoutShipping,
                    'orders_count' => $returnedCount
                ],
            ];
            foreach ($statusRevenues as $sr) {
                $statusRevenue = (float) $sr['revenue'];
                $roas = $totalSpent > 0 ? ($statusRevenue / $totalSpent) : 0;
                $roasByStatus[] = [
                    'status' => $sr['key'],
                    'label' => $sr['label'],
                    'revenue' => $statusRevenue,
                    'revenue_without_shipping' => (float) $sr['revenue_without_shipping'],
                    'orders_count' => (int) $sr['orders_count'],
                    'spent' => $totalSpent,
                    'roas' => $roas,
                    'percentage' => $attributedRevenue > 0 ? ($statusRevenue / $attributedRevenue) * 100 : 0,
                ];
            }
            usort($roasByStatus, function ($a, $b) {
                return $b['roas'] <=> $a['roas'];
            });

            // Top products by revenue (from orders linked to this ad history)
            $topProducts = $this->getTopProductsForReceiptSocialIds($historyIds, 10, $totalSpent > 0 ? ($attributedRevenue / $totalSpent) : 0);
        }

        $trueRoas = $totalSpent > 0 ? ($attributedRevenue / $totalSpent) : 0;

        return response()->json([
            'account' => [
                'id' => $selectedAccount->id,
                'name' => $selectedAccount->name,
            ],
            'attributed_revenue' => $attributedRevenue,
            'total_revenue_without_shipping' => $totalRevenueWithoutShipping,
            'total_spent' => $totalSpent,
            'total_combined_orders' => $totalCombinedOrders,
            'true_roas' => $trueRoas,
            'roas_by_status' => $roasByStatus,
            'top_products' => $topProducts ?? [],
        ]);
    } 

    public function roasProducts(Request $request, $account)
    {
        if (!Schema::hasTable('ads_accounts')) {
            return response()->json(['error' => trans('Ads accounts table is not migrated yet.')], 400);
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return response()->json(['error' => trans('Account not found')], 404);
        }

        $detailId = $request->query('detail_id');
        $detailIdsFilter = null;
        if ($detailId && is_numeric($detailId)) {
            $detailIdsFilter = $this->getDetailIdsWithDescendants($selectedAccount->id, (int) $detailId);
            if (empty($detailIdsFilter)) {
                return response()->json(['error' => trans('Detail not found')], 404);
            }
        }

        // Get history IDs for this account (optionally scoped to a detail and its children)
        $historyIds = [];
        if (Schema::hasTable('ads_accounts_details') && Schema::hasTable('ads_accounts_history')) {
            $query = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $selectedAccount->id);
            if ($detailIdsFilter !== null) {
                $query->whereIn('ads_accounts_details.id', $detailIdsFilter);
            }
            $historyIds = $query->pluck('ads_accounts_history.id')->toArray();
        }

        $products = [];
        $attributedRevenue = 0;
        $totalSpent = 0;

        if (!empty($historyIds)) {
            $totalSpent = AdsAccountHistory::query()
                ->whereIn('id', $historyIds)
                ->sum('total_spent');

            $historyWithSales = AdsAccountHistory::query()
                ->whereIn('id', $historyIds)
                ->get(['id', 'sales']);
            $attributedRevenue = (float) $historyWithSales->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);

            $products = $this->getAllProductsForReceiptSocialIds($historyIds, $totalSpent);
        }

        return response()->json([
            'account' => [
                'id' => $selectedAccount->id,
                'name' => $selectedAccount->name,
            ],
            'products' => $products,
            'total_products' => count($products),
        ]);
    }

    /**
     * Get top products by revenue for the given receipt social IDs (from receipt_social_receipt_social_product pivot).
     * Returns array of [ 'name' => string, 'image' => string|null, 'revenue' => float ].
     */
    private function getTopProductsForReceiptSocialIds(array $receiptSocialIds, int $limit,int $totalSpent): array
    {
        if (empty($receiptSocialIds) || !Schema::hasTable('receipt_social_receipt_social_product')) {
            return [];
        }

        $details = ReceiptSocialProductPivot::query()
            ->whereIn('receipt_social_id', $receiptSocialIds)
            ->select('receipt_social_product_id', DB::raw('SUM(COALESCE(total_cost, price * quantity)) as revenue'))
            ->groupBy('receipt_social_product_id')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();

        if ($details->isEmpty()) {
            return [];
        }

        $productIds = $details->pluck('receipt_social_product_id')->filter()->unique()->values()->toArray();
        $products = ReceiptSocialProduct::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'name']);

        $productByName = $products->keyBy('id');
        $productImageById = [];
        foreach ($products as $p) {
            $productImageById[$p->id] = $p->getFirstMediaUrl('photos', 'preview') ?: null;
        }

        return $details->map(function ($row) use ($productByName, $productImageById) {
            $product = $productByName->get($row->receipt_social_product_id);
            $name = $product ? $product->name : ('Product #' . ($row->receipt_social_product_id ?? ''));
            return [
                'name' => $name,
                'image' => $productImageById[$row->receipt_social_product_id] ?? null,
                'revenue' => (float) ($row->revenue ?? 0),
            ];
        })->values()->toArray();
    }

    /**
     * Get all products by revenue for the given receipt social IDs (from receipt_social_receipt_social_product pivot).
     * Returns array of [ 'name' => string, 'revenue' => float, 'quantity' => int ].
     */
    private function getAllProductsForReceiptSocialIds(array $receiptSocialIds, int $totalSpent): array
    {
        if (empty($receiptSocialIds) || !Schema::hasTable('receipt_social_receipt_social_product')) {
            return [];
        }

        $details = ReceiptSocialProductPivot::query()
            ->whereIn('receipt_social_id', $receiptSocialIds)
            ->select(
                'receipt_social_product_id',
                DB::raw('SUM(COALESCE(total_cost, price * quantity)) as revenue'),
                DB::raw('SUM(quantity) as quantity')
            )
            ->groupBy('receipt_social_product_id')
            ->orderByDesc('revenue')
            ->get();

        if ($details->isEmpty()) {
            return [];
        }

        $productIds = $details->pluck('receipt_social_product_id')->filter()->unique()->values()->toArray();
        $products = ReceiptSocialProduct::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'name']);

        $productByName = $products->keyBy('id');

        return $details->map(function ($row) use ($productByName) {
            $product = $productByName->get($row->receipt_social_product_id);
            $name = $product ? $product->name : ('Product #' . ($row->receipt_social_product_id ?? ''));
            return [
                'name' => $name,
                'revenue' => (float) ($row->revenue ?? 0),
                'quantity' => (int) ($row->quantity ?? 0),
            ];
        })->values()->toArray();
    }
}
