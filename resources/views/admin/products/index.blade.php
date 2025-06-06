@extends('layouts.admin')
@section('content')
        <div style="margin-bottom: 10px;" class="row">
            @can('product_create')
                <div class="col-lg-6">
                    <a class="btn btn-success" href="{{ route('admin.products.create') }}">
                        {{ __('global.add') }} {{ __('cruds.product.title_singular') }}
                    </a>
                </div>
            @endcan
            @can('edit_prices')
                <div class="col-lg-6">
                    <a class="btn btn-info" href="#" data-toggle="modal" data-target="#editPrice">
                        تعديل الأسعار
                    </a>
                </div>
            @endcan
        </div>
    <form class="card" action="" id="sort_products">  
        <div class="card-header">
            <div style="display: flex;justify-content: space-between">
                <div> {{ __('global.search') }} {{ __('cruds.product.title') }}</div>
                
                <select class="form-control @isset($website_setting_id) isset @endisset" style="width: 200px"
                    name="website_setting_id" id="website_setting_id" onchange="sort_products()">
                    <option value="">أختر الموقع</option>
                    @foreach ($websites as $id => $entry)
                        <option value="{{ $id }}"
                            @isset($website_setting_id) @if ($website_setting_id == $id) selected @endif @endisset>
                            {{ $entry }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_id">{{ __('cruds.product.fields.category') }}</label>
                        <select class="form-control select2  {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id"  onchange="sub_categories_by_category()" > 
                            {{-- ajax call --}}
                        </select>
                        @if ($errors->has('category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.product.fields.category_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sub_category_id">{{ __('cruds.product.fields.sub_category') }}</label>
                        <select class="form-control select2  {{ $errors->has('sub_category') ? 'is-invalid' : '' }}"  name="sub_category_id" id="sub_category_id"  onchange="sub_sub_categories_by_category()"> 
                            {{-- ajax call --}}
                        </select>
                        @if ($errors->has('sub_category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sub_category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.product.fields.sub_category_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sub_sub_category_id">{{ __('cruds.product.fields.sub_sub_category') }}</label>
                        <select class="form-control select2  {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}" name="sub_sub_category_id" id="sub_sub_category_id">
                            {{-- ajax call --}}
                        </select>
                        @if ($errors->has('sub_sub_category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sub_sub_category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.product.fields.sub_sub_category_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="weight">{{ __('cruds.product.fields.weight') }}</label>
                        <select class="form-control @isset($weight) isset @endisset {{ $errors->has('weight') ? 'is-invalid' : '' }}" name="weight" id="weight" onchange="sort_products()">
                            <option value="">أختر</option>
                            @foreach(\App\Models\Product::WEIGHT_SELECT as $key => $entry)
                                <option value="{{ $key }}" @if($weight == $key) selected @endif>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('weight'))
                            <div class="invalid-feedback">
                                {{ $errors->first('weight') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.product.fields.weight_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control mb-2 @isset($published) isset @endisset" name="published" id="published" onchange="sort_products()">
                        <option value="">{{ __('global.extra.published') }}</option>
                        <option value="0" @isset($published) @if ($published == '0') selected @endif @endisset>
                            {{ __('global.extra.0_published') }}</option>
                        <option value="1" @isset($published) @if ($published == '1') selected @endif @endisset>
                            {{ __('global.extra.1_published') }}</option>
                    </select>  
                </div>
                <div class="col-md-2">
                    <select class="form-control mb-2 @isset($todays_deal) isset @endisset" name="todays_deal" id="todays_deal" onchange="sort_products()">
                        <option value="">{{ __('global.extra.todays_deal') }}</option>
                        <option value="0" @isset($todays_deal) @if ($todays_deal == '0') selected @endif @endisset>
                            {{ __('global.extra.0_todays_deal') }}</option>
                        <option value="1" @isset($todays_deal) @if ($todays_deal == '1') selected @endif @endisset>
                            {{ __('global.extra.1_todays_deal') }}</option>
                    </select>  
                </div>
                <div class="col-md-2">
                    <select class="form-control mb-2 @isset($featured) isset @endisset" name="featured" id="featured" onchange="sort_products()">
                        <option value="">{{ __('global.extra.featured') }}</option>
                        <option value="0" @isset($featured) @if ($featured == '0') selected @endif @endisset>
                            {{ __('global.extra.0_featured') }}</option>
                        <option value="1" @isset($featured) @if ($featured == '1') selected @endif @endisset>
                            {{ __('global.extra.1_featured') }}</option>
                    </select>  
                </div>
                <div class="col-md-2">
                    <select class="form-control mb-2 @isset($flash_deal) isset @endisset" name="flash_deal" id="flash_deal" onchange="sort_products()">
                        <option value="">{{ __('global.extra.flash_deal') }}</option>
                        <option value="0" @isset($flash_deal) @if ($flash_deal == '0') selected @endif @endisset>
                            {{ __('global.extra.0_flash_deal') }}</option>
                        <option value="1" @isset($flash_deal) @if ($flash_deal == '1') selected @endif @endisset>
                            {{ __('global.extra.1_flash_deal') }}</option>
                    </select>  
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-md-3">
                    <input type="submit" value="{{ __('global.search') }}" name="search"
                        class="btn btn-success btn-rounded btn-block">
                </div>
                <div class="col-md-3">
                    <a class="btn btn-warning btn-rounded btn-block"
                        href="{{ route('admin.products.index') }}">{{ __('global.reset') }}</a>
                </div>
                <div class="col-md-3">
                    <input type="submit" value="{{ __('global.download') }}" name="download" class="btn btn-info btn-rounded btn-block">
                </div>
            </div>
        </div>
    </form>
    
    <div class="card"> 
        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ __('cruds.product.fields.id') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.name') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.weight') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.unit_price') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.photos') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.statuses') }}
                        </th> 
                        <th>
                            {{ __('cruds.product.fields.current_stock') }}
                        </th>
                        <th>
                            {{ __('cruds.product.fields.categories') }}
                        </th>
                        <th>
                            {{ __('global.extra.website_setting_id') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    <!--  edit Price -->
    <div class="modal fade" id="editPrice" tabindex="-1" aria-labelledby="editPriceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPriceLabel">
                        تعديل الأسعار
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.products.edit_prices') }}" method="POST">
                        @csrf 
                        <div class="row"> 
                            <div class="form-group col-md-4">
                                <label class="">{{ __('cruds.product.fields.category') }}</label>
                                <select class="form-control  {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category" id="category">
                                    <option value disabled {{ old('category', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ old('category', '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('category'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('category') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ __('cruds.product.fields.category_helper') }}</span>
                            </div>  
                            <div class="form-group col-md-4">
                                <label class="">{{ __('cruds.product.fields.sub_category') }}</label>
                                <select class="form-control  {{ $errors->has('sub_category') ? 'is-invalid' : '' }}" name="sub_category" id="sub_category">
                                    <option value disabled {{ old('sub_category', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\SubCategory::all() as $sub_category)
                                        <option value="{{ $sub_category->id }}" {{ old('sub_category', '') === (string) $sub_category->id ? 'selected' : '' }}>{{ $sub_category->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('sub_category'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('sub_category') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ __('cruds.product.fields.sub_category_helper') }}</span>
                            </div>  
                            <div class="form-group col-md-4">
                                <label class="">{{ __('cruds.product.fields.sub_sub_category') }}</label>
                                <select class="form-control  {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}" name="sub_sub_category" id="sub_sub_category">
                                    <option value disabled {{ old('sub_sub_category', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\SubSubCategory::all() as $sub_sub_category)
                                        <option value="{{ $sub_sub_category->id }}" {{ old('sub_sub_category', '') === (string) $sub_sub_category->id ? 'selected' : '' }}>{{ $sub_sub_category->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('sub_sub_category'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('sub_sub_category') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ __('cruds.product.fields.sub_sub_category_helper') }}</span>
                            </div>  
                            <div class="form-group col-md-4">
                                <label class="">طريقة الحساب</label>
                                <select class="form-control {{ $errors->has('money_type') ? 'is-invalid' : '' }}" name="money_type" id="money_type"> 
                                    <option value="percentage">نسبة</option> 
                                    <option value="flat">قيمة</option> 
                                </select> 
                            </div>  
                            <div class="form-group col-md-4">
                                <label class="required" for="unit_price">سعر البيع</label>
                                <input class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}" type="number"
                                    name="unit_price" id="unit_price" value="{{ old('unit_price', '') }}" placeholder="النسبة او القيمة" step="0.01" required>
                                @if ($errors->has('unit_price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('unit_price') }}
                                    </div>
                                @endif  
                            </div>
                            <div class="form-group col-md-4">
                                <label class="required" for="purchase_price">سعر الجملة</label>
                                <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number"
                                    name="purchase_price" id="purchase_price" value="{{ old('purchase_price', '') }}" placeholder="النسبة او القيمة"  step="0.01" required>
                                @if ($errors->has('purchase_price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('purchase_price') }}
                                    </div>
                                @endif  
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script> 
        $(document).ready(function() {
            categories_by_website();
        });

        function categories_by_website() {
            var website_setting_id = $('#website_setting_id').val();
            $.post('{{ route('admin.website-settings.get_categories_by_website') }}', {
                _token: '{{ csrf_token() }}',
                website_setting_id: website_setting_id
            }, function(data) {
                $('#category_id').html(null);

                $('#category_id').append($('<option>', {
                        value: '',
                        text: 'أختر'
                    }));
                for (var i = 0; i < data.length; i++) {
                    $('#category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                } 
                $("#category_id > option").each(function() {
                    if(this.value == '{{request("category_id")}}'){
                        $("#category_id").val(this.value).change();
                    }
                }); 
                sub_categories_by_category();
                sub_sub_categories_by_category(); 
            });
        }

        function sub_categories_by_category(){ 
            var category_id = $('#category_id').val();
            $.post('{{ route('admin.products.get_sub_categories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
                $('#sub_category_id').html(null);
                $('#sub_sub_category_id').html(null);

                $('#sub_category_id').append($('<option>', {
                        value: '',
                        text: 'أختر'
                    }));
                for (var i = 0; i < data.length; i++) {
                    $('#sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    })); 
                } 
                $("#sub_category_id > option").each(function() {
                    if(this.value == '{{request("sub_category_id")}}'){
                        $("#sub_category_id").val(this.value).change();
                    }
                }); 
                sub_sub_categories_by_category();
            });
        }

        function sub_sub_categories_by_category(){  
            var sub_category_id = $('#sub_category_id').val();
            $.post('{{ route('admin.products.get_sub_sub_categories_by_subcategory') }}',{_token:'{{ csrf_token() }}', sub_category_id:sub_category_id}, function(data){
                $('#sub_sub_category_id').html(null);   
                $('#sub_sub_category_id').append($('<option>', {
                        value: '',
                        text: 'أختر'
                    }));
                for (var i = 0; i < data.length; i++) {
                    $('#sub_sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    })); 
                } 
                $("#sub_sub_category_id > option").each(function() {
                    if(this.value == '{{request("sub_sub_category_id")}}'){
                        $("#sub_sub_category_id").val(this.value).change();
                    }
                }); 
            });
        }

        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.products.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if(data == 1){
                    showAlert('success', 'Success', '');
                }else{
                    showAlert('danger', 'Something went wrong', '');
                }
            });
        }

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('product_delete')
                let deleteButtonTrans = '{{ __('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.products.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ __('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ __('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],  
                ajax: "{{ route('admin.products.index') }}?website_setting_id={{request('website_setting_id')}}&category_id={{request('category_id')}}&sub_category_id={{request('sub_category_id')}}&sub_sub_category_id={{request('sub_sub_category_id')}}&weight={{request('weight')}}&flash_deal={{request('flash_deal')}}&published={{request('published')}}&featured={{request('featured')}}&todays_deal={{request('todays_deal')}}",
                data:{_token:'{{ csrf_token() }}','website_setting_id' : 4},
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'weight',
                        name: 'weight'
                    },
                    {
                        data: 'unit_price',
                        name: 'unit_price'
                    },
                    {
                        data: 'photos',
                        name: 'photos',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'statuses',
                        name: 'statuses',
                        sortable: false,
                        searchable: false
                    },
                    {
                        data: 'current_stock',
                        name: 'current_stock'
                    },
                    {
                        data: 'categories',
                        name: 'categories'
                    },
                    {
                        data: 'website_site_name',
                        name: 'website.site_name'
                    }, 
                    {
                        data: 'actions',
                        name: '{{ __('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 25,
                stateSave: true
            };
            let table = $('.datatable-Product').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
        
        function sort_products(el) {
            $('#sort_products').submit();
        }
    </script>
@endsection
