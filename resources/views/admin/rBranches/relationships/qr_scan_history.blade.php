<div class="card">
    <div class="card-header">
        <div class="card">
            <div class="card-header">
                أضافة Scan جديدة
            </div>
            <div class="card-body">
                <form action="{{ route('admin.qr-products.printmore') }}" method="POST" id="print_more_form2">
                    @csrf 
                    <input type="hidden" name="ids" id="input-ids2">
                </form>
                <form method="POST" action="{{ route('admin.qr-products.start_scan') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="r_branch_id" value="{{ $rBranch->id }}">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="required" for="name">التسمية</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                name="name" id="name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <br>
                            <button class="btn btn-success" type="submit">
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-qr_scan_history">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            id
                        </th>
                        <th>
                            التسمية
                        </th>
                        <th>
                            التاريخ
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qr_scan_history as $key => $history)
                        <tr data-entry-id="{{ $history->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $history->id ?? '' }}
                            </td>
                            <td>
                                {{ $history->name ?? '' }}
                            </td>
                            <td>
                                {{ $history->created_at ?? '' }}
                            </td>
                            <td>
                                <a href="#" onclick="view_scanner('{{$history->id}}')" class="btn btn-info btn-xs">scan</a>
                                <a href="#" onclick="view_result('{{$history->id}}')" class="btn btn-success btn-xs">result</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

@section('scripts')
    @parent
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest/umd/index.min.js"></script>
    <script type="text/javascript"> 
        var qr_scan_history_id = null;

        function view_scanner(id){
            qr_scan_history_id = id;
            $.post('{{ route('admin.qr-products.view_scanner') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data); 
                load_cam();
            }); 
        }

        function view_result(id){ 
            $.post('{{ route('admin.qr-products.view_result') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                r_branch_id: '{{ $rBranch->id }}',
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);  
            }); 
        }
        function load_needs(qr_scan_history_id,qr_product_rbranch_id){ 
            $.post('{{ route('admin.qr-products.load_needs') }}', {
                _token: '{{ csrf_token() }}',
                qr_product_rbranch_id: qr_product_rbranch_id,
                qr_scan_history_id: qr_scan_history_id, 
            }, function(data) {
                $('#load-needs').html(data);
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)  
                let deleteButtonTrans = "طباعة المحدد"
                let deleteButton = {
                    text: deleteButtonTrans, 
                    className: 'btn-warning',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ __('global.datatables.zero_selected') }}') 
                            return
                        }

                        if (confirm('{{ __('global.areYouSure') }}')) {
                            $('#input-ids2').val(ids);
                            $('#print_more_form2').submit();  
                        }
                    }
                }
                dtButtons.push(deleteButton) 
                $.extend(true, $.fn.dataTable.defaults, {
                    orderCellsTop: true, 
                    pageLength: 100,
                });
                let table = $('.datatable-names2:not(.ajaxTable)').DataTable({
                    buttons: dtButtons
                })
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            }); 
        }
        function save_print(qr_scan_history_id,name_id){ 
            $('#tr_needs_'+name_id).css('background-color','#53e753');
            $.post('{{ route('admin.qr-products.save_print') }}', {
                _token: '{{ csrf_token() }}',
                id: qr_scan_history_id, 
                name_id: name_id, 
            }, function(data) { 
            }); 
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-qr_scan_history:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        }) 
        


        function decodeOnce(codeReader, selectedDeviceId) {
            codeReader.decodeFromInputVideoDevice(selectedDeviceId, 'video').then((result) => {
                console.log(result.text) 

                    $.post('{{ route('admin.qr-products.qr_output') }}', {
                        _token: '{{ csrf_token() }}',
                        id: qr_scan_history_id, 
                        branch_id: '{{$rBranch->id}}', 
                        code: result.text, 
                    }, function(data) {
                        console.log(data); 
                        const myTimeout = setTimeout(load_cam, 2500);
                        if (data.status) {
                            $('#scanned-table tbody').prepend(data.tr);
                            $('#results tbody').html(data.results);
                        } else {
                            showAlert('error',data.message);
                        }
                    });
            }).catch((err) => {
                console.error(err)
                document.getElementById('result').textContent = err
                const myTimeout = setTimeout(load_cam, 2500);
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
