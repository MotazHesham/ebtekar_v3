<div class="card">
    <div class="card-header">

        <div class="card">
            <div class="card-header">
                أضافة منتج عام
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.qr-products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="r_branch_id" value="{{ $rBranch->id }}">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="required" for="product">المنتج</label>
                            <input class="form-control {{ $errors->has('product') ? 'is-invalid' : '' }}" type="text"
                                name="product" id="product" value="{{ old('product') }}" required>
                            @if ($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label>أخد الأسماء من منتج أخر</label>
                            <select name="qr_product_id" id="" class="form-control">
                                <option disabled selected>أختر منتج</option>
                                @foreach (\App\Models\QrProduct::all() as $product)
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
                        <div class="form-group col-md-6">
                            <label><br></label>
                            <button class="btn btn-success btn-block btn-warning text-dark" type="submit">
                                أضافة المنتج
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-qr_products2">
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
                            الاسماء
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qr_products as $key => $raw)
                        <tr data-entry-id="{{ $raw->id }}">
                            <form action="{{ route('admin.qr-products.update_product') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $raw->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $raw->id ?? '' }}
                                </td>
                                <td>
                                    <span style="display: none">{{ $raw->product }}</span>
                                    <input type="text" name="product" value="{{ $raw->product ?? 0 }}"
                                        class="form-control" id="">
                                </td>
                                <td>
                                    {{ $raw->names->count() }}
                                </td>
                                <td>

                                    <button type="submit" class="btn btn-xs btn-success">
                                        تحديث
                                    </button>

                                    <a class="btn btn-xs btn-danger"
                                        href="{{ route('admin.qr-products.destroy_product', $raw->id) }}"
                                        onclick="return confirm('are you sure?')">
                                        {{ __('global.delete') }}
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
            let table = $('.datatable-qr_products2:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
