@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header">{{ __('delivery.scan.title') }}</div>
                <div class="card-body">
                    @if ($isLocal)
                        <label class="small">{{ __('delivery.scan.enter_code') }}</label>
                        <input type="text" id="scan-code" class="form-control form-control-lg" autofocus autocomplete="off">
                    @else
                        <div id="reader" class="mb-2" style="max-width:100%"></div>
                        <label class="small">{{ __('delivery.scan.or_manual') }}</label>
                        <input type="text" id="scan-code" class="form-control" autocomplete="off">
                    @endif
                    <button type="button" class="btn btn-primary btn-block mt-2" id="btn-lookup">{{ __('delivery.scan.lookup') }}</button>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card d-none" id="order-card">
                <div class="card-header">{{ __('delivery.scan.order_found') }}</div>
                <div class="card-body" id="order-details"></div>
                <div class="card-footer">
                    <button type="button" class="btn btn-success btn-action" data-action="delivered">{{ __('delivery.actions.mark_delivered') }}</button>
                    <button type="button" class="btn btn-warning btn-action" data-action="returned">{{ __('returns::actions.register') }}</button>
                    <div class="mt-2 return-reason-wrap d-none">
                        <select id="return-reason" class="form-control">
                            @foreach ($returnReasons as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    @unless($isLocal)
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @endunless
    <script>
        $(function () {
            const lookupUrl = @json(route('admin.courier.scan.lookup'));
            const applyUrl = @json(route('admin.courier.scan.apply'));
            const token = @json(csrf_token());
            const confirmMsg = @json(__('delivery.messages.confirm_status'));
            const confirmOk = @json(__('delivery.messages.confirm_ok'));
            const confirmCancel = @json(__('delivery.messages.confirm_cancel'));
            let currentCode = null;

            function lookup() {
                const code = $('#scan-code').val().trim();
                if (!code) return;
                currentCode = code;
                $.ajax({
                    url: lookupUrl,
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    data: { code: code },
                    success: function (data) {
                        const d = data.data || data;
                        $('#order-details').html(
                            '<p><strong>' + (d.order_num || '') + '</strong></p>' +
                            '<p>' + (d.client_name || '') + ' — ' + (d.phone_number || '') + '</p>' +
                            '<p>' + (d.governorate || '') + ' / ' + (d.region || '') + '</p>' +
                            '<p class="small text-muted">' + (d.full_address || '') + '</p>' +
                            '<p><span class="badge badge-info">' + (d.status_label || d.status || '') + '</span></p>'
                        );
                        $('#order-card').removeClass('d-none');
                    },
                    error: function (xhr) {
                        showAlert('error', xhr.responseJSON?.message || 'Error', xhr.responseJSON?.message || 'Error');
                        $('#order-card').addClass('d-none');
                    }
                });
            }

            $('#btn-lookup, #scan-code').on('click keypress', function (e) {
                if (e.type === 'keypress' && e.which !== 13) return;
                if (e.type === 'click' && e.target.id !== 'btn-lookup') return;
                lookup();
            });

            $('.btn-action').on('click', function () {
                const action = $(this).data('action');
                if (!currentCode) return;

                if (action === 'returned' && $('.return-reason-wrap').hasClass('d-none')) {
                    $('.return-reason-wrap').removeClass('d-none');
                    return;
                }

                swal({
                    title: confirmMsg,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmOk,
                    cancelButtonText: confirmCancel,
                }).then(function (result) {
                    if (!result.value) return;
                    $.ajax({
                        url: applyUrl,
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                        data: {
                            code: currentCode,
                            action: action,
                            return_reason: $('#return-reason').val(),
                        },
                        success: function (res) {
                            showAlert('success', res.message, res.message);
                            $('#order-card').addClass('d-none');
                            $('#scan-code').val('').focus();
                            currentCode = null;
                        },
                        error: function (xhr) {
                            showAlert('error', xhr.responseJSON?.message || 'Error', xhr.responseJSON?.message || 'Error');
                        }
                    });
                });
            });

            @unless($isLocal)
            if (typeof Html5Qrcode !== 'undefined') {
                const scanner = new Html5Qrcode('reader');
                scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: 250 },
                    function (decoded) {
                        $('#scan-code').val(decoded);
                        lookup();
                    }
                ).catch(function () {});
            }
            @endunless
        });
    </script>
@endsection
