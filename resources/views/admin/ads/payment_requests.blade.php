@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Payment Requests') }}
        <a class="btn btn-success btn-sm float-right" href="{{ route('admin.ads.payment_requests.create') }}">
            <i class="fa fa-plus"></i> {{ trans('New Payment Request') }}
        </a>
    </div>
    <div class="card-body">
        <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
            <a class="btn btn-default btn-sm" href="javascript:void(0)" onclick="showTransferModal()"><i class="fa fa-exchange"></i> {{ trans('Transfer') }}</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ trans('Account') }}</th>
                        <th>{{ trans('Code') }}</th>
                        <th>{{ trans('Type') }}</th>
                        <th>{{ trans('Amount') }}</th>
                        <th>{{ trans('Status') }}</th>
                        <th>{{ trans('Date') }}</th>
                        <th>{{ trans('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($paymentRequests ?? collect()) as $paymentRequest)
                        @php
                            $account = $paymentRequest->adsAccount;
                            $transactionType = $paymentRequest->transaction_type ?? ($paymentRequest->isTransfer() ? 'transfer' : 'charge');
                            $status = strtolower($paymentRequest->status ?? 'pending');
                            $isPending = $status === 'pending';
                            $receiptUrl = $paymentRequest->receipt ? asset('storage/' . $paymentRequest->receipt) : null;
                        @endphp
                        <tr>
                            <td>
                                @if($paymentRequest->isTransfer() && $paymentRequest->fromAdsAccount && $paymentRequest->toAdsAccount)
                                    <div class="small">
                                        <div><strong>From:</strong> {{ $paymentRequest->fromAdsAccount->name ?? 'N/A' }}</div>
                                        <div><strong>To:</strong> {{ $paymentRequest->toAdsAccount->name ?? 'N/A' }}</div>
                                    </div>
                                @elseif($account)
                                    <div>{{ $account->name }}</div>
                                    <small class="text-muted">ID: {{ $account->id }}</small>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $paymentRequest->code ?? '—' }}</td>
                            <td>
                                @if($transactionType === 'transfer')
                                    <span class="badge badge-info">{{ ucfirst($transactionType) }}</span>
                                @else
                                    <span class="badge badge-primary">{{ ucfirst($transactionType) }}</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($paymentRequest->amount ?? 0, 2) }}</strong></td>
                            <td>
                                @if($status === 'pending')
                                    <span class="badge badge-warning">{{ ucfirst($paymentRequest->status ?? 'Pending') }}</span>
                                @elseif(in_array($status, ['approved', 'completed', 'paid']))
                                    <span class="badge badge-success">{{ ucfirst($paymentRequest->status ?? 'Pending') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($paymentRequest->status ?? 'Pending') }}</span>
                                @endif
                            </td>
                            <td>{{ $paymentRequest->add_date ? $paymentRequest->add_date->format('M d, Y') : '—' }}</td>
                            <td>
                                @if($isPending && $account)
                                    <a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="showPaymentModal({{ $paymentRequest->id }}, '{{ addslashes($account->name ?? 'N/A') }}', {{ $paymentRequest->amount }})" title="{{ trans('Pay') }}"><i class="fa fa-credit-card"></i></a>
                                @endif

                                @if(!$isPending && $receiptUrl)
                                    <a href="{{ $receiptUrl }}" class="btn btn-xs btn-secondary" target="_blank" title="{{ trans('View Receipt') }}">
                                        <i class="fa fa-file"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ trans('No payment requests found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($paymentRequests) && $paymentRequests->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                <div class="text-muted small">
                    @php $paginator = $paymentRequests; @endphp
                    {{ trans('Showing') }} {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} {{ trans('of') }} {{ $paginator->total() }}
                </div>
                <nav>{{ $paymentRequests->links() }}</nav>
            </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="paymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Process Payment') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ trans('Account') }}</label>
                        <div id="paymentAccountName" class="font-weight-bold"></div>
                    </div>
                    <div class="form-group">
                        <label>{{ trans('Amount') }}</label>
                        <div id="paymentAmount" class="font-weight-bold"></div>
                    </div>
                    <div class="form-group">
                        <label class="required" for="transaction_reference">{{ trans('Transaction Reference') }}</label>
                        <input type="text" id="transaction_reference" name="transaction_reference" class="form-control" required>
                        <div id="transaction_reference_error" class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="required">{{ trans('Payment Receipt') }}</label>
                        <input type="file" name="receipt" class="form-control" accept="image/*,.pdf" id="receipt-file">
                        <span class="help-block">{{ trans('Upload payment receipt (image or PDF)') }}</span>
                        <div id="receipt-error" class="invalid-feedback" style="display: none;">{{ trans('Please upload a payment receipt') }}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('Mark as Paid') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="transferForm" method="POST" action="{{ route('admin.ads.payment_requests.transfer') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('Transfer Amount') }}</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required" for="from_ad_account">{{ trans('From Account') }}</label>
                        <select id="from_ad_account" name="from_ad_account" class="form-control" required>
                            <option value="">{{ trans('Select Account') }}</option>
                            @foreach($accounts ?? [] as $account)
                                <option value="{{ $account->id }}" data-balance="{{ $account->balance ?? 0 }}">{{ $account->name }} ({{ trans('Balance') }}: {{ number_format($account->balance ?? 0, 2) }})</option>
                            @endforeach
                        </select>
                        <div id="from_ad_account_error" class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="required" for="to_ad_account">{{ trans('To Account') }}</label>
                        <select id="to_ad_account" name="to_ad_account" class="form-control" required>
                            <option value="">{{ trans('Select Account') }}</option>
                            @foreach($accounts ?? [] as $account)
                                <option value="{{ $account->id }}">{{ $account->name }} ({{ trans('Balance') }}: {{ number_format($account->balance ?? 0, 2) }})</option>
                            @endforeach
                        </select>
                        <div id="to_ad_account_error" class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="required" for="transfer_amount">{{ trans('Amount') }}</label>
                        <input type="number" id="transfer_amount" name="amount" step="0.01" min="0.01" class="form-control" required>
                        <small id="balance_info" class="help-block"></small>
                        <div id="transfer_amount_error" class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label for="transfer_reason">{{ trans('Reason') }}</label>
                        <textarea id="transfer_reason" name="reason" rows="3" class="form-control" placeholder="{{ trans('Optional reason for the transfer') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('Transfer') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    function showPaymentModal(paymentRequestId, accountName, amount) {
        $('#paymentAccountName').text(accountName);
        $('#paymentAmount').text(parseFloat(amount).toFixed(2));
        $('#paymentForm').attr('action', '{{ route("admin.ads.payment_requests.pay", ":id") }}'.replace(':id', paymentRequestId));
        $('#transaction_reference').val('');
        $('#receipt-file').val('');
        $('#paymentModal').modal('show');
    }

    function showTransferModal() {
        $('#transferForm')[0].reset();
        $('#balance_info').text('');
        $('#from_ad_account_error').hide();
        $('#to_ad_account_error').hide();
        $('#transfer_amount_error').hide();
        $('#transferModal').modal('show');
    }

    $(document).ready(function() {
        $('#from_ad_account').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var balance = parseFloat(selectedOption.data('balance') || 0);
            $('#balance_info').text($(this).val() ? '{{ trans("Available balance") }}: ' + balance.toFixed(2) : '');
        });

        $('#transferForm').on('submit', function(e) {
            var fromAccount = $('#from_ad_account').val();
            var toAccount = $('#to_ad_account').val();
            var amount = parseFloat($('#transfer_amount').val()) || 0;
            var fromBalance = parseFloat($('#from_ad_account option:selected').data('balance') || 0);
            var hasError = false;

            if (!fromAccount) { $('#from_ad_account_error').text('{{ trans("Please select a source account") }}').show(); hasError = true; } else { $('#from_ad_account_error').hide(); }
            if (!toAccount) { $('#to_ad_account_error').text('{{ trans("Please select a destination account") }}').show(); hasError = true; } else { $('#to_ad_account_error').hide(); }
            if (fromAccount === toAccount) { $('#to_ad_account_error').text('{{ trans("Source and destination accounts must be different") }}').show(); hasError = true; }
            if (!amount || amount <= 0) { $('#transfer_amount_error').text('{{ trans("Please enter a valid amount") }}').show(); hasError = true; }
            else if (amount > fromBalance) { $('#transfer_amount_error').text('{{ trans("Insufficient balance in source account") }}').show(); hasError = true; }
            else { $('#transfer_amount_error').hide(); }

            if (hasError) { e.preventDefault(); return false; }
        });

        $('#paymentForm').on('submit', function(e) {
            var transactionRef = $('#transaction_reference').val();
            var receipt = $('#receipt-file').val();
            if (!transactionRef || transactionRef.trim() === '') {
                e.preventDefault();
                $('#transaction_reference_error').text('{{ trans("Transaction reference is required") }}').show();
                $('#transaction_reference').addClass('is-invalid');
                return false;
            }
            if (!receipt || receipt.trim() === '') {
                e.preventDefault();
                $('#receipt-error').show();
                $('#receipt-file').addClass('is-invalid');
                return false;
            }
        });
    });
</script>
@endsection
