@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ __('tracking::scan.receive_title') }}
            </div>
            <div class="card-body text-center">
                <ul class="nav nav-pills nav-fill mb-3" id="scan-mode-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-mode="barcode" role="tab">
                            {{ __('tracking::scan.scan_mode_barcode') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-mode="qr" role="tab">
                            {{ __('tracking::scan.scan_mode_qr') }}
                        </a>
                    </li>
                </ul>

                <section class="container">
                    <div id="panel-barcode">
                        <label for="bar_code">{{ __('tracking::scan.barcode_label') }}</label>
                        <input class="form-control" type="text" autofocus name="bar_code" id="bar_code"
                               placeholder="S-123 / O-456 / C-789">
                    </div>

                    <div id="panel-qr" style="display: none;">
                        @if($manualInput)
                            <p class="text-muted small">{{ __('tracking::scan.qr_local_hint') }}</p>
                            <label for="qr_code">{{ __('tracking::scan.qr_label') }}</label>
                            <input class="form-control" type="text" name="qr_code" id="qr_code">
                        @else
                            <div class="mb-2">
                                <button type="button" class="btn btn-sm btn-success" id="qrStartButton">Start</button>
                                <button type="button" class="btn btn-sm btn-info" id="qrResetButton">Stop</button>
                            </div>
                            <video id="qr-video" width="280" height="180" style="border: 1px solid #ccc; max-width: 100%;"></video>
                            <pre class="small text-left mt-2 mb-0" style="display:none"><code id="qr-result"></code></pre>
                        @endif
                    </div>

                    @if($partners)
                        <div class="row mt-3">
                            <div class="col-12 text-left">
                                <label for="shipping_partner_id">{{ __('cruds.deliveryOrder.fields.shipping_partner') }}</label>
                                <select class="form-control demo-select2" name="shipping_partner_id" id="shipping_partner_id">
                                    <option value="0">{{ __('tracking::scan.select_partner') }}</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="text-center">
            <h3>{{ __('tracking::scan.results') }} <b class="badge badge-success" id="scan-counter">0</b></h3>
            <div id="order_scanned"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var counter = 1;
    var qrReader = null;
    var qrDecodeActive = false;

    function partnerSelected() {
        @if($partners)
        if ($('#shipping_partner_id').val() == 0) {
            alert('{{ __('tracking::scan.select_partner_alert') }}');
            return false;
        }
        @endif
        return true;
    }

    function resolvePartnerId() {
        if (!$('#shipping_partner_id').length) {
            return null;
        }
        var id = $('#shipping_partner_id').val();
        return (id && id !== '0') ? id : null;
    }

    function handleReceiveResponse(data) {
        if (!data || typeof data !== 'object') {
            showAlert('error', '{{ __('tracking::scan.failed_short') }}', '{{ __('tracking::scan.failed_short') }}');
            return;
        }

        if (data.message) {
            $('#order_scanned').prepend(data.message);
        }

        var summary = data.order_num
            ? data.order_num
            : ($('<div>').html(data.message || '').text().trim() || '{{ __('tracking::scan.failed_short') }}');

        if (data.status == 1) {
            showAlert('success', '{{ __('tracking::scan.receive_ok_short') }}', summary);
            $('#scan-counter').text(counter++);
        } else {
            showAlert('error', '{{ __('tracking::scan.failed_short') }}', summary);
        }
    }

    function submitReceiveScan(code) {
        code = (code || '').trim();
        if (!code || !partnerSelected()) {
            return;
        }

        $.ajax({
            url: '{{ route('admin.tracking.scan.receive') }}',
            method: 'POST',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                code: code,
                shipping_partner_id: resolvePartnerId()
            },
            success: handleReceiveResponse,
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : '{{ __('tracking::scan.failed_short') }}';
                showAlert('error', '{{ __('tracking::scan.failed_short') }}', msg);
            }
        });
    }

    function setScanMode(mode) {
        $('#scan-mode-tabs .nav-link').removeClass('active');
        $('#scan-mode-tabs .nav-link[data-mode="' + mode + '"]').addClass('active');
        $('#panel-barcode').toggle(mode === 'barcode');
        $('#panel-qr').toggle(mode === 'qr');

        if (mode === 'barcode') {
            stopQrCamera();
            setTimeout(function () { $('#bar_code').focus(); }, 100);
        } else {
            @if($manualInput)
            setTimeout(function () { $('#qr_code').focus(); }, 100);
            @else
            startQrCamera();
            @endif
        }
    }

    $('#scan-mode-tabs .nav-link').on('click', function (e) {
        e.preventDefault();
        setScanMode($(this).data('mode'));
    });

    $('#bar_code').on('change', function () {
        var code = $(this).val();
        submitReceiveScan(code);
        $(this).val(null).focus();
    });

    @if($manualInput)
    $('#qr_code').on('change', function () {
        var code = $(this).val();
        submitReceiveScan(code);
        $(this).val(null).focus();
    });
    @endif

    function stopQrCamera() {
        qrDecodeActive = false;
        if (qrReader) {
            try {
                qrReader.reset();
            } catch (e) {}
        }
    }

    @if(!$manualInput)
    var qrSelectedDeviceId = null;

    function decodeQrOnce() {
        if (!qrReader || qrDecodeActive) {
            return;
        }
        qrDecodeActive = true;

        qrReader.decodeFromVideoDevice(qrSelectedDeviceId, 'qr-video').then(function (result) {
            stopQrCamera();
            submitReceiveScan(result.text);
            setTimeout(startQrCamera, 3000);
        }).catch(function (err) {
            qrDecodeActive = false;
            document.getElementById('qr-result').textContent = err;
            setTimeout(decodeQrOnce, 1500);
        });
    }

    function startQrCamera() {
        if (typeof ZXing === 'undefined') {
            return;
        }
        if (!qrReader) {
            qrReader = new ZXing.BrowserQRCodeReader();
            qrReader.getVideoInputDevices().then(function (devices) {
                if (devices.length) {
                    qrSelectedDeviceId = devices[0].deviceId;
                }
                decodeQrOnce();
            }).catch(function (err) {
                console.error(err);
            });
            return;
        }
        decodeQrOnce();
    }

    $('#qrStartButton').on('click', function () {
        stopQrCamera();
        startQrCamera();
    });

    $('#qrResetButton').on('click', function () {
        stopQrCamera();
        document.getElementById('qr-result').textContent = '';
    });
    @endif
</script>

@if(!$manualInput)
<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
@endif
@endsection
