<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdsAccount;
use App\Models\AdsAccountDetail;
use App\Models\AdsAccountHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdsHistoryController extends Controller
{
    /**
     * List history records for an account detail (ad) — paginated.
     */
    public function index(Request $request, $account, $detail)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $accountDetail = AdsAccountDetail::where('id', $detail)
            ->where('ad_account_id', $selectedAccount->id)
            ->firstOrFail();

        $history = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1);
        $totalSpent = 0;
        $totalRevenue = 0;
        $overallRoas = 0;
        $totalOrders = 0;
        $chartData = collect();

        if (Schema::hasTable('ads_accounts_history')) {
            $perPage = (int) $request->query('per_page', 15);
            $perPage = $perPage >= 1 && $perPage <= 100 ? $perPage : 15;

            $history = AdsAccountHistory::query()
                ->where('ad_account_detail_id', $accountDetail->id)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->paginate($perPage, ['id', 'ad_account_detail_id', 'total_spent', 'date', 'sales'])
                ->withQueryString();

            // Attach order statistics from sales JSON for current page items
            $history->getCollection()->each(function ($historyItem) {
                $historyItem->orders_count = (int) ($historyItem->getOrdersCountFromSales() ?? 0);
                $historyItem->revenue = (float) ($historyItem->getRevenueFromSales() ?? 0);
            });

            // Aggregate statistics from ALL history for this detail (not just current page)
            $allForTotals = AdsAccountHistory::query()
                ->where('ad_account_detail_id', $accountDetail->id)
                ->get(['total_spent', 'sales']);
            $totalSpent = $allForTotals->sum('total_spent');
            $totalRevenue = (float) $allForTotals->sum(fn ($h) => $h->getRevenueFromSales() ?? 0);
            $totalOrders = (int) $allForTotals->sum(fn ($h) => $h->getOrdersCountFromSales() ?? 0);
            $overallRoas = $totalSpent > 0 ? ($totalRevenue / $totalSpent) : 0;

            // ROAS chart: last 7 records by date
            $chartItems = AdsAccountHistory::query()
                ->where('ad_account_detail_id', $accountDetail->id)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->limit(7)
                ->get(['date', 'total_spent', 'sales'])
                ->reverse()
                ->values();
            $chartItems->each(function ($item) {
                $item->revenue = (float) ($item->getRevenueFromSales() ?? 0);
            });
            $chartData = $chartItems->map(function ($item) {
                $spent = (float) ($item->total_spent ?? 0);
                $revenue = (float) ($item->revenue ?? 0);
                $roas = $spent > 0 ? ($revenue / $spent) : 0;
                return [
                    'date' => $item->date ? $item->date->format('M d') : '',
                    'roas' => $roas,
                ];
            })->values();
        }

        return view('admin.ads.accounts_details_history', compact('selectedAccount', 'accountDetail', 'history', 'totalSpent', 'totalRevenue', 'overallRoas', 'totalOrders', 'chartData'));
    }

    /**
     * Show form to create a new history record.
     */
    public function create($account, $detail)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details') || !Schema::hasTable('ads_accounts_history')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $accountDetail = AdsAccountDetail::where('id', $detail)
            ->where('ad_account_id', $selectedAccount->id)
            ->firstOrFail();

        // Calculate balance information
        $currentBalance = (float) ($selectedAccount->balance ?? 0);

        // Calculate total spent from all history records for this account
        $totalSpent = 0;
        if (Schema::hasTable('ads_accounts_details') && Schema::hasTable('ads_accounts_history')) {
            $totalSpent = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $selectedAccount->id)
                ->sum('ads_accounts_history.total_spent');
        }

        $originalBalance = $currentBalance + $totalSpent; // Original balance before any spending
        $remainingBalance = $currentBalance; // Current remaining balance

        return view('admin.ads.accounts_details_history_create', compact('selectedAccount', 'accountDetail', 'currentBalance', 'originalBalance', 'remainingBalance', 'totalSpent'));
    }

    /**
     * Store a new history record.
     */
    public function store(Request $request, $account, $detail)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details') || !Schema::hasTable('ads_accounts_history')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $accountDetail = AdsAccountDetail::where('id', $detail)
            ->where('ad_account_id', $selectedAccount->id)
            ->firstOrFail();

        $validated = $request->validate([
            'total_spent' => ['required', 'numeric', 'min:0'],
            'date'        => ['required', 'date'],
        ]);

        // Check if there is already a spent record for this ad (detail) with this date
        $existingForDate = AdsAccountHistory::query()
            ->where('ad_account_detail_id', $accountDetail->id)
            ->whereDate('date', $validated['date'])
            ->exists();
        if ($existingForDate) {
            return back()
                ->withInput()
                ->with('error', trans('A history record already exists for this ad on the selected date. Please edit it or choose another date.'));
        }

        $newSpent = (float) $validated['total_spent'];
        $currentBalance = (float) ($selectedAccount->balance ?? 0);

        // Check if balance is sufficient
        if ($currentBalance < $newSpent) {
            return back()
                ->withInput()
                ->with('error', trans('Insufficient balance. Available: ') . $currentBalance . trans(', Required: ') .  $newSpent );
        }

        DB::beginTransaction();
        try {
            // Create history record
            AdsAccountHistory::create([
                'ad_account_detail_id' => $accountDetail->id,
                'total_spent'          => $validated['total_spent'],
                'date'                 => $validated['date'],
            ]);

            // Deduct from account balance
            if (Schema::hasColumn('ads_accounts', 'balance')) {
                $newBalance = $currentBalance - $newSpent;
                $selectedAccount->update([
                    'balance' => $newBalance,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id])
                ->with('success', trans('History record created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', trans('An error occurred while creating the history record: ') . $e->getMessage());
        }
    }

    /**
     * Show form to edit a history record (only when Total Spent is 0).
     */
    public function edit($account, $detail, $history)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details') || !Schema::hasTable('ads_accounts_history')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $accountDetail = AdsAccountDetail::where('id', $detail)
            ->where('ad_account_id', $selectedAccount->id)
            ->firstOrFail();

        $historyItem = AdsAccountHistory::where('id', $history)
            ->where('ad_account_detail_id', $accountDetail->id)
            ->firstOrFail();

        // // Allow edit when Total Spent is 0, or when Total Spent > 0 and user is admin
        // $totalSpentVal = (float) ($historyItem->total_spent ?? 0);
        // if ($totalSpentVal != 0 && (!auth()->check() || auth()->user()->user_type !== 'admin')) {
        //     return redirect()
        //         ->route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id])
        //         ->with('error', trans('Editing records with Total Spent > 0 is allowed for admin only.'));
        // }

        // Calculate balance information
        $currentBalance = (float) ($selectedAccount->balance ?? 0);

        // Calculate total spent from all history records for this account
        $totalSpent = 0;
        if (Schema::hasTable('ads_accounts_details') && Schema::hasTable('ads_accounts_history')) {
            $totalSpent = AdsAccountHistory::query()
                ->join('ads_accounts_details', 'ads_accounts_details.id', '=', 'ads_accounts_history.ad_account_detail_id')
                ->where('ads_accounts_details.ad_account_id', $selectedAccount->id)
                ->sum('ads_accounts_history.total_spent');
        }

        $originalBalance = $currentBalance + $totalSpent; // Original balance before any spending
        $remainingBalance = $currentBalance; // Current remaining balance

        // Admin can edit date on any record; staff cannot change date
        $canEditDate = auth()->check() && auth()->user()->user_type === 'admin';

        return view('admin.ads.accounts_details_history_edit', compact('selectedAccount', 'accountDetail', 'historyItem', 'currentBalance', 'originalBalance', 'remainingBalance', 'totalSpent', 'canEditDate'));
    }

    /**
     * Update a history record (only when Total Spent is 0).
     */
    public function update(Request $request, $account, $detail, $history)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details') || !Schema::hasTable('ads_accounts_history')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $accountDetail = AdsAccountDetail::where('id', $detail)
            ->where('ad_account_id', $selectedAccount->id)
            ->firstOrFail();

        $historyItem = AdsAccountHistory::where('id', $history)
            ->where('ad_account_detail_id', $accountDetail->id)
            ->firstOrFail();

        // Allow update when Total Spent is 0, or when Total Spent > 0 and user is admin
        // $totalSpentVal = (float) ($historyItem->total_spent ?? 0);
        // if ($totalSpentVal != 0 && (!auth()->check() || auth()->user()->user_type !== 'admin')) {
        //     return redirect()
        //         ->route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id])
        //         ->with('error', trans('Editing records with Total Spent > 0 is allowed for admin only.'));
        // }

        $validated = $request->validate([
            'total_spent' => ['required', 'numeric', 'min:0'],
            'date'        => ['required', 'date'],
        ]);

        $oldSpent = (float) ($historyItem->total_spent ?? 0);
        $newSpent = (float) $validated['total_spent'];
        $spentDifference = $newSpent - $oldSpent;

        // Get current account balance
        $currentBalance = (float) ($selectedAccount->balance ?? 0);

        DB::beginTransaction();
        try {
            // If increasing spent, check if balance is sufficient
            if ($spentDifference > 0) {
                if ($currentBalance < $spentDifference) {
                    DB::rollBack();
                    return back()->with('error', trans('Insufficient balance. Available: ') . $currentBalance  . trans(', Required: ') . $spentDifference );
                }

                // Deduct the difference from balance
                $newBalance = $currentBalance - $spentDifference;
            } else {
                // If decreasing spent, add the difference back to balance
                $newBalance = $currentBalance - $spentDifference; // $spentDifference is negative, so this adds
            }

            // Update history record
            $historyItem->update([
                'total_spent' => $validated['total_spent'],
                'date'        => $validated['date'],
            ]);

            // Update account balance
            if (Schema::hasColumn('ads_accounts', 'balance')) {
                $selectedAccount->update([
                    'balance' => $newBalance,
                ]);
            }

            DB::commit();
            return redirect()
                ->route('admin.ads.accounts.details.history', [$selectedAccount->id, $accountDetail->id])
                ->with('success', trans('History record updated successfully. Balance updated.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', trans('An error occurred while updating the history record: ') . $e->getMessage());
        }
    }
}
