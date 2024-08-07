@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">

            </div>

            <div class="card-body text-center">

                <section class="container" id="cam-content">
                    <div class="mb-3">
                        <button class="btn btn-pill btn-lg btn-success" id="startButton">Start</button>
                        <button class="btn btn-pill btn-lg btn-info " id="resetButton">Stop</button>
                    </div>

                    <div>
                        <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
                    </div>

                    <div id="sourceSelectPanel" style="display:none">
                        <span for="sourceSelect">Change video source:</span>
                        <select id="sourceSelect" style="max-width:400px">
                        </select>
                    </div>

                    <div @if($type != 'shipment') style="display:none" @endif>
                        <select class="form-control demo-select2" name="delivery_man_id" id="delivery_man_id"   >
                            <option value="0">{{__('Select Delivery Man ...')}}</option>
                            @foreach($delivery_mans as $user)
                                    <option value="{{$user->id}}">{{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display: none" class="text-center">
                        <span for="decoding-style"> Decoding Style:</span>
                        <select id="decoding-style" size="1">
                            <option value="once">Decode once</option>
                            <option value="continuously">Decode continuously</option>
                        </select>
                    </div>

                    <span>Result:</span>
                    <pre><code id="result"></code></pre>
                </section>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="text-center">
            <h3>Scann Results</h3>
            <div  id="order_scanned"></div>
        </div>
    </div> 
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
<script type="text/javascript">
    load_cam();

    function decodeOnce(codeReader, selectedDeviceId) {
        codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
            console.log(result.text)
            @if($type == 'shipment')
                if($('#delivery_man_id').val() == 0){
                    alert('برجاء اختيار المندوب');
                    const myTimeout = setTimeout(load_cam, 5000);
                    return 0;
                }
            @endif

                $.post('{{ route('admin.qr_output') }}', {
                    _token: '{{ csrf_token() }}',
                    code: result.text,
                    type: '{{$type}}',
                    delivery_man_id : $('#delivery_man_id').val()
                }, function(data) {
                    console.log(data);

                    $('#order_scanned').prepend(data.message);
                    const myTimeout = setTimeout(load_cam, 5000);
                    if (data.status == 1) {
                        showAlert('success','تم الأرسال');
                    } else {
                        showAlert('error','لم يتم الأرسال');
                    }
                });
        }).catch((err) => {
            console.error(err)
            document.getElementById('result').textContent = err
            const myTimeout = setTimeout(load_cam, 5000);
        })
    }


    function load_cam() {

        let selectedDeviceId;
        const codeReader = new ZXing.BrowserQRCodeReader();
        console.log('ZXing code reader initialized');

        const decodingStyle = document.getElementById('decoding-style').value;

        decodeOnce(codeReader, selectedDeviceId);

        console.log(`Started decode from camera with id ${selectedDeviceId}`)

        codeReader.getVideoInputDevices()
            .then((videoInputDevices) => {

                const sourceSelect = document.getElementById('sourceSelect')
                selectedDeviceId = videoInputDevices[0].deviceId

                if (videoInputDevices.length >= 1) {
                    videoInputDevices.forEach((element) => {
                        const sourceOption = document.createElement('option')
                        sourceOption.text = element.label
                        sourceOption.value = element.deviceId
                        sourceSelect.appendChild(sourceOption)
                    })

                    sourceSelect.onchange = () => {
                        selectedDeviceId = sourceSelect.value;
                    };

                    const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                    sourceSelectPanel.style.display = 'block'
                }

                document.getElementById('startButton').addEventListener('click', () => {
                    const decodingStyle = document.getElementById('decoding-style').value;

                    decodeOnce(codeReader, selectedDeviceId);

                    console.log(`Started decode from camera with id ${selectedDeviceId}`)
                })

                document.getElementById('resetButton').addEventListener('click', () => {
                    codeReader.reset()
                    document.getElementById('result').textContent = '';
                    console.log('Reset.')
                })

            })

            .catch((err) => {
                console.error(err)
            })

    }
</script>

@endsection
