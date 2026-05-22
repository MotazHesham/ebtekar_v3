@extends('layouts.admin')

@php
    $manualInput = app()->environment('local');
@endphp

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"></div>

                <div class="card-body text-center">
                    <section class="container" id="cam-content">
                        @if ($manualInput)
                            <p class="text-muted small mb-3">
                                {{ __('Local environment: enter order code manually instead of camera scan.') }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="qr_code">{{ __('tracking::scan.barcode_label') }}</label>
                                    <input class="form-control" type="text" autofocus name="qr_code" id="qr_code">
                                </div>
                                <div class="col-md-6">
                                    <label for="shipping_partner_id">&nbsp;</label>
                                    <div @if ($type != 'shipment') style="display:none" @endif>
                                        <select class="form-control demo-select2" name="shipping_partner_id"
                                            id="shipping_partner_id">
                                            <option value="0">{{ __('tracking::scan.select_partner') }}</option>
                                            @foreach ($shipping_partners as $partner)
                                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <button class="btn btn-pill btn-lg btn-success" id="startButton">Start</button>
                                <button class="btn btn-pill btn-lg btn-info" id="resetButton">Stop</button>
                            </div>

                            <div>
                                <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
                            </div>

                            <div id="sourceSelectPanel" style="display:none">
                                <span for="sourceSelect">Change video source:</span>
                                <select id="sourceSelect" style="max-width:400px"></select>
                            </div>

                            <div class="row mt-3" @if ($type != 'shipment') style="display:none" @endif>
                                <div class="col-12">
                                    <label for="shipping_partner_id">{{ __('tracking::scan.select_partner') }}</label>
                                    <select class="form-control demo-select2" name="shipping_partner_id"
                                        id="shipping_partner_id">
                                        <option value="0">{{ __('tracking::scan.select_partner') }}</option>
                                        @foreach ($shipping_partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div style="display: none" class="text-center">
                                <span for="decoding-style">Decoding Style:</span>
                                <select id="decoding-style" size="1">
                                    <option value="once">Decode once</option>
                                    <option value="continuously">Decode continuously</option>
                                </select>
                            </div>

                            <span>Result:</span>
                            <pre><code id="result"></code></pre>
                        @endif
                    </section>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center">
                <h3>Scann Results @if (!$manualInput)
                        <b class="badge badge-success" id="scan-counter">0</b>
                    @endif
                </h3>
                <div id="order_scanned"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        load_cam();

        function shipmentPartnerSelected() {
            @if ($type == 'shipment')
                if ($('#shipping_partner_id').val() == 0) {
                    alert('{{ __('tracking::scan.select_partner_alert') }}');
                    return false;
                }
            @endif
            return true;
        }

        function submitQrCode(code) {
            if (!code || !shipmentPartnerSelected()) {
                return;
            }

            $.post('{{ route('admin.qr_output') }}', {
                _token: '{{ csrf_token() }}',
                code: code,
                type: '{{ $type }}',
                shipping_partner_id: $('#shipping_partner_id').val()
            }, function(data) {
                $('#order_scanned').prepend(data.message);
                if (data.status == 1) {
                    showAlert('success', 'تم الأرسال');
                    @if (!$manualInput)
                        $('#scan-counter').html(counter++);
                        setTimeout(load_cam, 5000);
                    @endif
                } else {
                    showAlert('error', 'لم يتم الأرسال');
                    @if (!$manualInput)
                        setTimeout(load_cam, 5000);
                    @endif
                }
            });
        }
    </script>

    @if ($manualInput)
        <script type="text/javascript">
            $('#qr_code').on('change', function() {
                var code = $(this).val();
                submitQrCode(code);
                $(this).val(null);
                $(this).focus();
            });
        </script>
    @else
        <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
        <script type="text/javascript">
            load_cam();

            function decodeOnce(codeReader, selectedDeviceId) {
                codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
                    submitQrCode(result.text);
                }).catch((err) => {
                    console.error(err);
                    document.getElementById('result').textContent = err;
                    setTimeout(load_cam, 5000);
                });
            }

            function load_cam() {
                let selectedDeviceId;
                const codeReader = new ZXing.BrowserQRCodeReader();

                decodeOnce(codeReader, selectedDeviceId);

                codeReader.getVideoInputDevices()
                    .then((videoInputDevices) => {
                        const sourceSelect = document.getElementById('sourceSelect');
                        selectedDeviceId = videoInputDevices[0].deviceId;

                        if (videoInputDevices.length >= 1) {
                            videoInputDevices.forEach((element) => {
                                const sourceOption = document.createElement('option');
                                sourceOption.text = element.label;
                                sourceOption.value = element.deviceId;
                                sourceSelect.appendChild(sourceOption);
                            });

                            sourceSelect.onchange = () => {
                                selectedDeviceId = sourceSelect.value;
                            };

                            document.getElementById('sourceSelectPanel').style.display = 'block';
                        }

                        document.getElementById('startButton').addEventListener('click', () => {
                            decodeOnce(codeReader, selectedDeviceId);
                        });

                        document.getElementById('resetButton').addEventListener('click', () => {
                            codeReader.reset();
                            document.getElementById('result').textContent = '';
                        });
                    })
                    .catch((err) => {
                        console.error(err);
                    });
            }
        </script>
    @endif
@endsection
