<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use App\Models\AdsAccount; 
use App\Models\AdsPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class AdsPaymentRequestController extends Controller
{
    /**
     * List all payment requests (paginated).
     */
    public function index(Request $request)
    {
        if (! Gate::allows('ads_payment_request_access')) {
            abort(403, 'Unauthorized action.');
        }
        $paymentRequests = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1);

        if (Schema::hasTable('ads_payments_requests')) {
            $query = AdsPaymentRequest::query()
                ->with(['adsAccount', 'fromAdsAccount', 'toAdsAccount'])
                ->orderByDesc('id');

            $search = $request->query('search');
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('adsAccount', fn ($aq) => $aq->where('name', 'like', '%' . $search . '%'))
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            }

            $perPage = (int) $request->query('per_page', 15);
            $perPage = $perPage >= 1 && $perPage <= 100 ? $perPage : 15;
            $paymentRequests = $query->paginate($perPage)->withQueryString();
        }

        $accounts = Schema::hasTable('ads_accounts') ? AdsAccount::query()->orderBy('name')->get() : collect();

        return view('admin.ads.payment_requests', compact('paymentRequests', 'accounts'));
    }

    /**
     * Show form to create a new payment request.
     */
    public function create()
    {
        if (!Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads accounts table is not migrated yet.'));
        }

        $accounts = AdsAccount::query()->orderBy('name')->get();

        return view('admin.ads.payment_request_create', compact('accounts'));
    }

    /**
     * Store a new payment request.
     */
    public function store(Request $request)
    {
        if (!Schema::hasTable('ads_payments_requests') || !Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads payment requests table is not migrated yet.'));
        }

        $validated = $request->validate([
            'ad_account_id' => ['required', 'exists:ads_accounts,id'],
            'code'          => ['nullable', 'string', 'max:255'],
            'amount'        => ['required', 'numeric', 'min:0.01'],
            'add_date'      => ['required', 'date'],
        ]);

        AdsPaymentRequest::create([
            'ad_account_id'    => $validated['ad_account_id'],
            'code'             => $validated['code'] ?? null,
            'amount'           => $validated['amount'],
            'status'           => 'pending',
            'add_date'         => $validated['add_date'],
            'transaction_type' => 'charge',
        ]);

        return redirect()->route('admin.ads.payment_requests.index')->with('success', trans('Payment request created successfully'));
    }

    /**
     * Mark a payment request as paid and add amount to account balance.
     */
    public function pay(Request $request, $paymentRequest)
    {
        if (!Schema::hasTable('ads_payments_requests')) {
            return back()->with('error', trans('Ads payment requests table is not migrated yet.'));
        }

        $paymentRequestModel = AdsPaymentRequest::with('adsAccount')->findOrFail($paymentRequest);

        if (strtolower($paymentRequestModel->status) !== 'pending') {
            return back()->with('error', trans('Only pending payment requests can be paid.'));
        }

        $validated = $request->validate([
            'transaction_reference' => ['required', 'string', 'max:255'],
            'receipt'               => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Update payment request
            $paymentRequestModel->update([
                'transaction_reference' => $validated['transaction_reference'],
                'receipt'               => $validated['receipt'],
                'status'                => 'paid',
            ]);

            // Add amount to account balance
            if ($paymentRequestModel->adsAccount && Schema::hasColumn('ads_accounts', 'balance')) {
                $account = $paymentRequestModel->adsAccount;
                $currentBalance = (float) ($account->balance ?? 0);
                $paymentAmount = (float) $paymentRequestModel->amount;
                $newBalance = $currentBalance + $paymentAmount;

                $account->update([
                    'balance' => $newBalance,
                ]);
            }

            DB::commit(); 

            return redirect()->route('admin.ads.payment_requests.index')->with('success', trans('Payment request marked as paid successfully and amount added to account balance'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', trans('An error occurred while processing the payment: ') . $e->getMessage());
        }
    }

    /**
     * Transfer amount from one ad account to another.
     */
    public function transfer(Request $request)
    {
        if (!Schema::hasTable('ads_payments_requests') || !Schema::hasTable('ads_accounts')) {
            return back()->with('error', trans('Ads payment requests table is not migrated yet.'));
        }

        $validated = $request->validate([
            'from_ad_account' => ['required', 'exists:ads_accounts,id'],
            'to_ad_account'   => ['required', 'exists:ads_accounts,id', 'different:from_ad_account'],
            'amount'          => ['required', 'numeric', 'min:0.01'],
            'reason'          => ['nullable', 'string', 'max:500'],
        ]);

        $fromAccount = AdsAccount::findOrFail($validated['from_ad_account']);
        $toAccount = AdsAccount::findOrFail($validated['to_ad_account']);

        // Check if from account has sufficient balance
        if (Schema::hasColumn('ads_accounts', 'balance')) {
            $currentBalance = (float) ($fromAccount->balance ?? 0);
            $transferAmount = (float) $validated['amount'];
            
            if ($currentBalance < $transferAmount) {
                return back()->with('error', trans('Insufficient balance in the source account.'));
            }
        }

        DB::beginTransaction();
        try { 

            // Create payment request record for the transfer
            $paymentRequest = AdsPaymentRequest::create([
                'ad_account_id'      => $validated['to_ad_account'], // Main account is the recipient
                'from_ad_account_id' => $validated['from_ad_account'],
                'to_ad_account_id'   => $validated['to_ad_account'], 
                'amount'             => $validated['amount'],
                'status'             => 'paid', // Transfer is immediately paid
                'reason'             => $validated['reason'] ?? null,
                'add_date'           => now(),
                'transaction_type'   => 'transfer',
            ]);

            // Deduct amount from source account
            if (Schema::hasColumn('ads_accounts', 'balance')) {
                $fromCurrentBalance = (float) ($fromAccount->balance ?? 0);
                $fromAccount->update([
                    'balance' => $fromCurrentBalance - $transferAmount,
                ]);
            }

            // Add amount to destination account
            if (Schema::hasColumn('ads_accounts', 'balance')) {
                $toCurrentBalance = (float) ($toAccount->balance ?? 0);
                $toAccount->update([
                    'balance' => $toCurrentBalance + $transferAmount,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.ads.payment_requests.index')->with('success', trans('Transfer completed successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', trans('An error occurred while processing the transfer: ') . $e->getMessage());
        }
    }
}
