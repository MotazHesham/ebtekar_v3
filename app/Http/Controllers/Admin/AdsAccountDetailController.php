<?php

namespace App\Http\Controllers\Admin;   

use App\Http\Controllers\Controller;
use App\Models\AdsAccount;
use App\Models\AdsAccountDetail;
use App\Models\AdsAccountHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdsAccountDetailController extends Controller
{
    /**
     * Show form to create a new detail under an account.
     */
    public function create($account)
    {
        if (!Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads accounts table is not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $parentOptions = collect();
        if (Schema::hasTable('ads_accounts_details')) {
            $parentOptions = AdsAccountDetail::query()
                ->with('parent')
                ->where('ad_account_id', $selectedAccount->id)
                ->where(function ($q) {
                    $q->whereNull('type')->orWhere('type', '!=', 'ad');
                })
                ->orderBy('name')
                ->get();
        }

        return view('admin.ads.accounts_details_create', compact('selectedAccount', 'parentOptions'));
    }

    /**
     * Store a new detail under an account.
     */
    public function store(Request $request, $account)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'utm_key'   => ['required', 'string', 'max:255'],
            'type'      => ['required', 'string', Rule::in(['campaign', 'ad_set', 'ad'])],
            'parent_id' => [
                'nullable',
                Rule::exists('ads_accounts_details', 'id')->where(function ($q) use ($selectedAccount) {
                    $q->where('ad_account_id', $selectedAccount->id);
                }),
            ],
        ]);

        AdsAccountDetail::create([
            'ad_account_id' => $selectedAccount->id,
            'parent_id'     => $validated['parent_id'] ?? null,
            'name'          => $validated['name'],
            'utm_key'       => $validated['utm_key'],
            'type'          => $validated['type'],
        ]);

        return redirect()
            ->route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id])
            ->with('success', trans('Detail created successfully'));
    }

    /**
     * List unassigned top-level details for assigning to an account.
     */
    public function assignIndex(Request $request)
    {
        if (!Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads accounts details table is not migrated yet.'));
        }

        $unassignedDetails = AdsAccountDetail::query()
            ->whereNull('ad_account_id')
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $accounts = collect();
        if (Schema::hasTable('ads_accounts')) {
            $accounts = AdsAccount::query()->orderBy('name')->get(['id', 'name']);
        }

        return view('admin.ads.assign_campaign', compact('unassignedDetails', 'accounts'));
    }

    /**
     * Assign a detail (campaign) and all its related children (ad_sets, ads) to an account.
     */
    public function assignToAccount(Request $request)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $validated = $request->validate([
            'detail_id'  => ['required', 'integer', Rule::exists('ads_accounts_details', 'id')->whereNull('ad_account_id')],
            'account_id' => ['required', 'integer', Rule::exists('ads_accounts', 'id')],
        ]);

        $detail = AdsAccountDetail::findOrFail($validated['detail_id']);
        $accountId = (int) $validated['account_id'];

        // Include this detail (campaign) and all its descendants (children at every level)
        $detailAndChildIds = $this->getDetailAndDescendantIds($detail->id);
        AdsAccountDetail::whereIn('id', $detailAndChildIds)->update(['ad_account_id' => $accountId]);

        return redirect()
            ->route('admin.ads.accounts.assign-campaign')
            ->with('success', trans('Campaign assigned to account successfully.'));
    }

    /**
     * Get detail id and all descendant ids (recursive).
     */
    protected function getDetailAndDescendantIds(int $detailId): array
    {
        $ids = [$detailId];
        $childIds = AdsAccountDetail::where('parent_id', $detailId)->pluck('id')->toArray();
        foreach ($childIds as $childId) {
            $ids = array_merge($ids, $this->getDetailAndDescendantIds($childId));
        }
        return $ids;
    }

    /**
     * Show form to create a detail and assign it to an account (standalone).
     */
    public function createStandalone()
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $accounts = AdsAccount::query()->orderBy('name')->get(['id', 'name']);

        return view('admin.ads.accounts_details_create_standalone', compact('accounts'));
    }

    /**
     * Store a detail and assign it to the selected account (standalone).
     */
    public function storeStandalone(Request $request)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'utm_key'    => ['required', 'string', 'max:255'],
            'type'       => ['required', 'string', Rule::in(['campaign', 'ad_set', 'ad'])],
            'account_id' => ['required', 'integer', Rule::exists('ads_accounts', 'id')],
        ]);

        $accountId = (int) $validated['account_id'];

        AdsAccountDetail::create([
            'ad_account_id' => $accountId,
            'parent_id'     => null,
            'name'          => $validated['name'],
            'utm_key'       => $validated['utm_key'],
            'type'          => $validated['type'],
        ]);

        return redirect()
            ->route('admin.ads.accounts.index', ['account_id' => $accountId])
            ->with('success', trans('Detail created and linked to account.'));
    }

    /**
     * Show form to edit a detail.
     */
    public function edit($account, $detail)
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

        $parentOptions = collect();
        if (Schema::hasTable('ads_accounts_details')) {
            $parentOptions = AdsAccountDetail::query()
                ->with('parent')
                ->where('ad_account_id', $selectedAccount->id)
                ->where('id', '!=', $accountDetail->id)
                ->where(function ($q) {
                    $q->whereNull('type')->orWhere('type', '!=', 'ad');
                })
                ->orderBy('name')
                ->get();
        }

        return view('admin.ads.accounts_details_edit', compact('selectedAccount', 'accountDetail', 'parentOptions'));
    }

    /**
     * Update a detail.
     */
    public function update(Request $request, $account, $detail)
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

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'utm_key'   => ['required', 'string', 'max:255'],
            'type'      => ['required', 'string', Rule::in(['campaign', 'ad_set', 'ad'])],
            'parent_id' => [
                'nullable',
                Rule::exists('ads_accounts_details', 'id')->where(function ($q) use ($selectedAccount, $detail) {
                    $q->where('ad_account_id', $selectedAccount->id)
                      ->where('id', '!=', $detail);
                }),
            ],
        ]);

        if ($validated['parent_id'] == $detail) {
            return back()->with('error', trans('A detail cannot be its own parent.'));
        }

        $accountDetail->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'name'      => $validated['name'],
            'utm_key'   => $validated['utm_key'],
            'type'      => $validated['type'],
        ]);

        return redirect()
            ->route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id])
            ->with('success', trans('Detail updated successfully'));
    }

    /**
     * Delete a detail and its children.
     */
    public function destroy($account, $detail)
    {
        if (!Schema::hasTable('ads_accounts') || !Schema::hasTable('ads_accounts_details')) {
            return back()->with('error', trans('Ads tables are not migrated yet.'));
        }

        $selectedAccount = AdsAccount::find($account);
        if (!$selectedAccount) {
            return redirect()->route('admin.ads.accounts.index')->with('error', trans('Account not found'));
        }

        $detailIds = $this->getDetailIdsDeletionOrder($selectedAccount->id, (int) $detail);
        if (empty($detailIds)) {
            return redirect()->route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id])
                ->with('error', trans('Detail not found'));
        }

        DB::beginTransaction();
        try {
            if (Schema::hasTable('ads_accounts_history')) {
                AdsAccountHistory::query()->whereIn('ad_account_detail_id', $detailIds)->delete();
            }
            AdsAccountDetail::query()->whereIn('id', $detailIds)->delete();
            DB::commit();
            return redirect()->route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id])
                ->with('success', trans('Detail and its children deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', trans('An error occurred while deleting the detail.'));
        }
    }

    /**
     * Get detail IDs in deletion order (children first, then parent).
     */
    private function getDetailIdsDeletionOrder(int $accountId, int $detailId): array
    {
        $detail = AdsAccountDetail::query()
            ->where('ad_account_id', $accountId)
            ->where('id', $detailId)
            ->first();
        if (!$detail) {
            return [];
        }
        $children = AdsAccountDetail::query()
            ->where('ad_account_id', $accountId)
            ->where('parent_id', $detailId)
            ->pluck('id')
            ->toArray();
        $ordered = [];
        foreach ($children as $childId) {
            $ordered = array_merge($ordered, $this->getDetailIdsDeletionOrder($accountId, $childId));
        }
        $ordered[] = $detail->id;
        return $ordered;
    }
}
