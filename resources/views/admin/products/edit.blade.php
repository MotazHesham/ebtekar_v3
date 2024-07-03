@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.products.update_product") }}" enctype="multipart/form-data" id="update_product"> 
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        معلوات المنتج
                    </div>
        
                    <div class="card-body">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                                id="name" value="{{ old('name', $product->name) }}" required>
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                        </div>
                        <div class="row"> 
                            <div class="form-group col-md-4">
                                <label class="required">{{ trans('cruds.product.fields.weight') }}</label>
                                <select class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" name="weight" id="weight" required>
                                    <option value disabled {{ old('weight', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\Product::WEIGHT_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('weight', $product->weight) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('weight'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('weight') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.weight_helper') }}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="required">{{ trans('cruds.product.fields.special') }}</label>
                                <select class="form-control {{ $errors->has('special') ? 'is-invalid' : '' }}" name="special" id="special" required>
                                    <option value disabled {{ old('special', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\Product::SPECIAL_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('special', $product->special) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('special'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('special') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.special_helper') }}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="required">{{ trans('cruds.product.fields.require_photos') }}</label>
                                <select class="form-control {{ $errors->has('require_photos') ? 'is-invalid' : '' }}" name="require_photos" id="require_photos" required>
                                    <option value disabled {{ old('require_photos', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                    @foreach(App\Models\Product::SPECIAL_SELECT as $key => $label)
                                        <option value="{{ $key }}" {{ old('require_photos', $product->require_photos) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('require_photos'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('require_photos') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.require_photos_helper') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="required" for="category_id">{{ trans('cruds.product.fields.category') }}</label>
                            <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                                @foreach ($categories as $id => $entry)
                                    <option value="{{ $id }}" {{ old('category_id',$product->category->id) == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sub_category_id">{{ trans('cruds.product.fields.sub_category') }}</label>
                            <select class="form-control select2 {{ $errors->has('sub_category') ? 'is-invalid' : '' }}"  name="sub_category_id" id="sub_category_id" required>
                                {{-- ajax call --}}
                            </select>
                            @if ($errors->has('sub_category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sub_category') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.sub_category_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sub_sub_category_id">{{ trans('cruds.product.fields.sub_sub_category') }}</label>
                            <select class="form-control select2 {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}" name="sub_sub_category_id" id="sub_sub_category_id">
                                {{-- ajax call --}}
                            </select>
                            @if ($errors->has('sub_sub_category'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sub_sub_category') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.sub_sub_category_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tags">{{ trans('cruds.product.fields.tags') }}</label> 
                            <input type="text" class="form-control {{ $errors->has('tags') ? 'is-invalid' : '' }}" name="tags[]" value="{{ $product->tags }}" placeholder="add tags ..." data-role="tagsinput">
                            @if ($errors->has('tags'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tags') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.tags_helper') }}</span>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        صور المنتج
                    </div>
        
                    <div class="card-body"> 
                        <div class="form-group">
                            <label class="required" for="photos">{{ trans('cruds.product.fields.photos') }}</label>
                            <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}" id="photos-dropzone">
                            </div>
                            @if ($errors->has('photos'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('photos') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.photos_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="object_3d">3D Object</label>
                            <div class="needsclick dropzone {{ $errors->has('object_3d') ? 'is-invalid' : '' }}" id="object_3d-dropzone">
                            </div>
                            @if ($errors->has('object_3d'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('object_3d') }}
                                </div>
                            @endif
                            <span class="help-block">only (.glb)</span>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        فيديوهات المنتج
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label for="video_provider">{{ trans('cruds.product.fields.video_provider') }}</label> 
                            <select class="form-control {{ $errors->has('video_provider') ? 'is-invalid' : '' }}"
                                name="video_provider" id="video_provider">
                                <option value disabled {{ old('video_provider', $product->video_provider) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                                @foreach (App\Models\Product::VIDEO_PROVIDER_SELECT as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('video_provider', $product->video_provider) === (string) $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('video_provider'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('video_provider') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.video_provider_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="video_link">{{ trans('cruds.product.fields.video_link') }}</label>
                            <input class="form-control {{ $errors->has('video_link') ? 'is-invalid' : '' }}" type="text"
                                name="video_link" id="video_link" value="{{ old('video_link', $product->video_link) }}">
                            @if ($errors->has('video_link'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('video_link') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.video_link_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        سعر المنتج
                    </div>
        
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="required" for="unit_price">{{ trans('cruds.product.fields.unit_price') }}</label>
                                <input class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}" type="number"
                                    name="unit_price" id="unit_price" value="{{ old('unit_price', $product->unit_price) }}" step="0.01" required>
                                @if ($errors->has('unit_price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('unit_price') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.unit_price_helper') }}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required" for="purchase_price">{{ trans('cruds.product.fields.purchase_price') }}</label>
                                <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number"
                                    name="purchase_price" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" step="0.01"
                                    required>
                                @if ($errors->has('purchase_price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('purchase_price') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.purchase_price_helper') }}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ trans('cruds.product.fields.discount_type') }}</label>
                                <select class="form-control {{ $errors->has('discount_type') ? 'is-invalid' : '' }}"
                                    name="discount_type" id="discount_type">
                                    <option value disabled {{ old('discount_type', $product->discount_type) === null ? 'selected' : '' }}>
                                        {{ trans('global.pleaseSelect') }}</option>
                                    @foreach (App\Models\Product::DISCOUNT_TYPE_SELECT as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('discount_type', $product->discount_type) === (string) $key ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('discount_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount_type') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.discount_type_helper') }}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="discount">{{ trans('cruds.product.fields.discount') }}</label>
                                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number"
                                    name="discount" id="discount" value="{{ old('discount', $product->discount) }}" step="0.01">
                                @if ($errors->has('discount'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.discount_helper') }}</span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="current_stock">{{ trans('cruds.product.fields.current_stock') }}</label>
                                <input class="form-control {{ $errors->has('current_stock') ? 'is-invalid' : '' }}" type="number"
                                    name="current_stock" id="current_stock" value="{{ old('current_stock', $product->current_stock) }}" step="0.01">
                                @if ($errors->has('current_stock'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('current_stock') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.product.fields.current_stock_helper') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        أنواع المنتج
                    </div> 
                    <div class="card-body">
                        <div class="form-group">
                            <label for="colors">{{ trans('cruds.product.fields.colors') }}</label>
                            <select class="form-control select2 color-var-select {{ $errors->has('colors') ? 'is-invalid' : '' }}" name="colors[]" id="colors" multiple>
                                @foreach ($colors as $code => $entry)
                                    <option value="{{ $code }}" @if($product->colors != null && in_array($code, json_decode($product->colors, true))) selected @endif> {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('colors'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('colors') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.colors_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="attributes">{{ trans('cruds.product.fields.attributes') }}</label>
                            <select class="form-control select2 {{ $errors->has('attributes') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes" multiple>
                                @foreach ($attributes as $id => $entry)
                                    <option value="{{ $id }}" @if($product->attributes != null && in_array($id, json_decode($product->attributes, true))) selected @endif> {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('attributes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('attributes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.attributes_helper') }}</span>
                        </div>
                        <div id="attribute_options">
                            @foreach (json_decode($product->attribute_options) as $key => $option)
                                @php
                                    $attribute = \App\Models\Attribute::find($option->attribute_id);
                                @endphp
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="hidden" name="attribute_options[]" value="{{ $option->attribute_id }}">
                                            <input type="text" class="form-control" value="{{ $attribute->name ?? '' }}" placeholder="Choice Title" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="attribute_options_{{ $option->attribute_id }}[]" placeholder="Enter choice values" value="{{ implode(',', $option->values) }}" data-role="tagsinput" onchange="update_sku()">
                                        </div>
                                        <div class="col-md-2">
                                            <button onclick="delete_row(this)" class="btn btn-danger btn-icon">{{ trans('global.delete') }}</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="sku_combination_edit">
                    {{-- ajax call --}}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                وصف المنتج
            </div>
            <div class="card-body">
                <div class="row"> 
                    <div class="form-group col-md-6">
                        <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                            id="description">{!! old('description', $product->description) !!}</textarea>
                        @if ($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="pdf">{{ trans('cruds.product.fields.pdf') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('pdf') ? 'is-invalid' : '' }}" id="pdf-dropzone">
                        </div>
                        @if ($errors->has('pdf'))
                            <div class="invalid-feedback">
                                {{ $errors->first('pdf') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.pdf_helper') }}</span>
                    </div> 
                    <div class="form-group col-md-12">
                        <label for="slug">{{ trans('cruds.product.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                            name="slug" id="slug" value="{{ old('slug',$product->slug) }}">
                        @if ($errors->has('slug'))
                            <div class="invalid-feedback">
                                {{ $errors->first('slug') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.slug_helper') }}</span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="meta_title">{{ trans('cruds.product.fields.meta_title') }}</label>
                        <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text"
                            name="meta_title" id="meta_title" value="{{ old('meta_title',$product->meta_title) }}">
                        @if ($errors->has('meta_title'))
                            <div class="invalid-feedback">
                                {{ $errors->first('meta_title') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.meta_title_helper') }}</span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="meta_description">{{ trans('cruds.product.fields.meta_description') }}</label>
                        <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description"
                            id="meta_description">{{ old('meta_description', $product->meta_description) }}</textarea>
                        @if ($errors->has('meta_description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('meta_description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.meta_description_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-danger btn-lg btn-block" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success btn-lg btn-block" type="submit" name="arrange_photos">
                        حفظ وترتيب الصور
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        
        $(document).ready(function() {
            get_sub_categories_by_category(); 
            update_sku();
        });

        $('#attributes').on('change', function() {
            $('#attribute_options').html(null);
            $.each($("#attributes option:selected"), function(){ 
                add_more_attributes_option($(this).val(), $(this).text());
            });
            update_sku();
        });

        function add_more_attributes_option(i, name){
            $options  = '<div class="row">';
            $options += '<div class="col-md-4">';
            $options += '<input type="hidden" name="attribute_options[]" value="'+i+'">';
            $options += '<input type="text" class="form-control" value="'+name+'" placeholder="Choice Title" readonly>';
            $options += '</div>';
            $options += '<div class="col-md-8">';
            $options += '<input type="text" class="form-control" name="attribute_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()">';
            $options += '</div>';
            $options += '</div>';
            $('#attribute_options').append($options);

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        $('#colors').on('change', function() {
            update_sku();
        });

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });
        $('input[name="purchase_price"]').on('keyup', function() {
            update_sku();
        });

        $('input[name="name"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em){
            $(em).closest('.form-group').remove();
            update_sku();
        }

        function update_sku(){
            $.ajax({
                type:"POST",
                url:'{{ route('admin.products.sku_combination_edit') }}',
                data:$('#update_product').serialize(),
                success: function(data){ 
                    $('#sku_combination_edit').html(data);
                }
            });
        }
        
        $('#category_id').on('change', function() {
            get_sub_categories_by_category();
        });

        $('#sub_category_id').on('change', function() {
            get_sub_sub_categories_by_category();
        });

        function get_sub_categories_by_category(){ 
            var category_id = $('#category_id').val(); 
            $.post('{{ route('admin.products.get_sub_categories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
                $('#sub_category_id').html(null);
                $('#sub_sub_category_id').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#sub_category_id > option").each(function() {
                    if(this.value == '{{$product->sub_category_id}}'){
                        $("#sub_category_id").val(this.value).change();
                    }
                }); 

                get_sub_sub_categories_by_category();
            });
        }

        function get_sub_sub_categories_by_category(){  
            var sub_category_id = $('#sub_category_id').val();
            $.post('{{ route('admin.products.get_sub_sub_categories_by_subcategory') }}',{_token:'{{ csrf_token() }}', sub_category_id:sub_category_id}, function(data){
                $('#sub_sub_category_id').html(null);
                $('#sub_sub_category_id').append($('<option>', {
                    value: null,
                    text: null
                }));
                for (var i = 0; i < data.length; i++) {
                    $('#sub_sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    })); 
                } 
                $("#sub_sub_category_id > option").each(function() {
                    if(this.value == '{{$product->sub_sub_category_id}}'){
                        $("#sub_sub_category_id").val(this.value).change();
                    }
                }); 
            });
        }

    </script>
    <script>  

        $(document).ready(function() {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function(file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST',
                                            '{{ route('admin.products.storeCKEditorImages') }}',
                                            true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText =
                                            `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() {
                                            reject(genericErrorText)
                                        });
                                        xhr.addEventListener('abort', function() {
                                            reject()
                                        });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response
                                                    .message ?
                                                    `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                    `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                                );
                                            }

                                            $('form').append(
                                                '<input type="hidden" name="ck-media[]" value="' +
                                                response.id + '">');

                                            resolve({
                                                default: response.url
                                            });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(
                                                e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '{{ $product->id ?? 0 }}');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }
        });
    </script>

    <script>
        var uploadedPhotosMap = {}
        Dropzone.options.photosDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: 4, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 4,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="photos[]" value="' + response.name + '">')
                uploadedPhotosMap[file.name] = response.name
            },
            removedfile: function(file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedPhotosMap[file.name]
                }
                $('form').find('input[name="photos[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($product) && $product->photos)
                    var files = {!! json_encode($product->photos) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="photos[]" value="' + file.file_name + '">')
                    }
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        Dropzone.options.pdfDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: 5, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5
            },
            success: function(file, response) {
                $('form').find('input[name="pdf"]').remove()
                $('form').append('<input type="hidden" name="pdf" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="pdf"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($product) && $product->pdf)
                    var file = {!! json_encode($product->pdf) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="pdf" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
    <script>
        Dropzone.options.object3dDropzone = {
            url: '{{ route('admin.products.storeMedia') }}',
            maxFilesize: 5, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5
            },
            success: function(file, response) {
                $('form').find('input[name="object_3d"]').remove()
                $('form').append('<input type="hidden" name="object_3d" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="object_3d"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($product) && $product->object_3d)
                    var file = {!! json_encode($product->object_3d) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="object_3d" value="' + file.file_name + '">')
                    this.options.maxFiles = this.options.maxFiles - 1
                @endif
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
