<div class="card">
    <div class="card-header">
        <div class="card">
            <div class="card-header">
                أضافة منتج
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.qr-products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="r_branch_id" value="{{ $rBranch->id }}"> 
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="required" for="product">المنتج</label>
                            <input class="form-control {{ $errors->has('product') ? 'is-invalid' : '' }}"
                                type="text" name="product" id="product" value="{{ old('product') }}"
                                required>
                            @if ($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif 
                        </div>
                        <div class="form-group col-md-6">
                            <label class="required" for="quantity"> الكمية</label>
                            <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number"
                                name="quantity" id="quantity" value="{{ old('quantity', '') }}" step="0.01" required>
                            @if ($errors->has('quantity'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('quantity') }}
                                </div>
                            @endif 
                            <span class="help-block">الكمية المفترض تواجدها في هذا الفرع دائما من هذا المنتج</span>
                        </div> 

                        <div class="form-group col-md-6">
                            <label >أخد الأسماء من منتج أخر</label>
                            <select name="qr_product_id" id="" class="form-control">
                                @foreach(\App\Models\QrProduct::all() as $product)
                                    <option value="{{ $product->id }}">{{ $product->product }}</option>
                                @endforeach
                            </select>
                        </div> 

                        <div class="form-group col-md-6">
                            <label for="keys">الأسماء</label>
                            <input class="form-control {{ $errors->has('keys') ? 'is-invalid' : '' }}" type="text"
                                name="keys" id="keys" placeholder="أضف اسم ..." data-role="tagsinput">
                            @if ($errors->has('keys'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('keys') }}
                                </div>
                            @endif 
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-qr_products">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            id
                        </th>
                        <th>
                            المنتج
                        </th>
                        <th>
                            الأسماء
                        </th>
                        <th>
                            الكمية
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qr_products as $key => $product)
                        <tr data-entry-id="{{ $product->id }}">
                            <form action="{{route('admin.qr-products.update')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $product->id ?? '' }}
                                </td>
                                <td>
                                    <input type="text" name="product" id="input-product-qr-{{$product->id}}" value="{{ $product->product ?? '' }}" class="form-control" id="">
                                </td>
                                <td>
                                    
                                    <a class="btn btn-xs btn-info text-white" style="cursor: pointer" onclick="view_names('{{ $product->id }}')">
                                        عرض <span class="badge badge-danger">{{ $product->names->count() ?? '' }}</span>
                                    </a>
                                </td>
                                <td>
                                    <input type="number" name="quantity" id="input-quantity-qr-{{$product->id}}" value="{{ $product->quantity ?? 0 }}" class="form-control" id=""> 
                                </td>
                                <td> 
                                
                                    <button type="submit" class="btn btn-xs btn-success" >
                                        تحديث
                                    </button> 
                                
                                    <a class="btn btn-xs btn-danger" href="{{route('admin.qr-products.destroy',$product->id)}}" onclick="return confirm('are you sure?')">
                                        {{ trans('global.delete') }}  
                                    </a>
                                </td>

                            </form>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-qr_products:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        }) 
        function view_names(id) {
            $.post('{{ route('admin.qr-products.show') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
                
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
                            alert('{{ trans('global.datatables.zero_selected') }}') 
                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $('#input-ids').val(ids);
                            $('#print_more_form').submit();  
                        }
                    }
                }
                dtButtons.push(deleteButton) 
                $.extend(true, $.fn.dataTable.defaults, {
                    orderCellsTop: true,
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 100,
                });
                let table = $('.datatable-names:not(.ajaxTable)').DataTable({
                    buttons: dtButtons
                })
                $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                    $($.fn.dataTable.tables(true)).DataTable()
                        .columns.adjust();
                });
            });
        }
    </script>
@endsection
