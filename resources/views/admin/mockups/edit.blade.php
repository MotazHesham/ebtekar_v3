@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.mockups.update_mockup') }}" enctype="multipart/form-data" id="update_mockup"> 
        @csrf
        <input type="hidden" name="id" value="{{$mockup->id}}" id="">
        <div class="card">
            <div class="card-header">
                معلومات الموكاب
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="required" for="name">{{ trans('cruds.mockup.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', $mockup->name) }}" required>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.mockup.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="required" for="category_id">{{ trans('cruds.mockup.fields.category') }}</label>
                        <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}"
                            name="category_id" id="category_id" required>
                            @foreach ($categories as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ (old('category_id') ? old('category_id') : $mockup->category->id ?? '') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.mockup.fields.category_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="required"
                            for="sub_category_id">{{ trans('cruds.mockup.fields.sub_category') }}</label>
                        <select class="form-control select2 {{ $errors->has('sub_category') ? 'is-invalid' : '' }}"
                            name="sub_category_id" id="sub_category_id" required>
                            {{-- ajax call --}}
                        </select>
                        @if ($errors->has('sub_category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sub_category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.mockup.fields.sub_category_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sub_sub_category_id">{{ trans('cruds.mockup.fields.sub_sub_category') }}</label>
                        <select class="form-control select2 {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}"
                            name="sub_sub_category_id" id="sub_sub_category_id">
                            {{-- ajax call --}}
                        </select>
                        @if ($errors->has('sub_sub_category'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sub_sub_category') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.mockup.fields.sub_sub_category_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                صور الموكاب
            </div>

            <div class="card-body">
                @for ($i = 1; $i <= 3; $i++)
                    <div class="alert alert-danger" id="preview_error_{{ $i }}" style="display:none">Height and
                        Width must be (450x550).</div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                @php
                                    $optional = '';
                                    $required = '';
                                @endphp

                                @if ($i != 1)
                                    @php
                                        $optional = 'Optional';
                                    @endphp
                                @else
                                    @php
                                        $required = 'required';
                                    @endphp
                                @endif

                                @php
                                    if ($i == 1) {
                                        $p = $mockup->preview_1;
                                    } elseif ($i == 2) {
                                        $p = $mockup->preview_2;
                                    } elseif ($i == 3) {
                                        $p = $mockup->preview_3;
                                    }
                                    if ($p != null) {
                                        $prev = json_decode($p);
                                        $width = $prev ? $prev->width : '';
                                        $height = $prev ? $prev->height : '';
                                        $top = $prev ? $prev->top : '';
                                        $left = $prev ? $prev->left : '';
                                        $name = $prev ? $prev->name : '';
                                    }
                                @endphp
                                <div>
                                    {{ __('Preview ' . $i) }} <small>({{ $optional }})</small><br>
                                    <small>max(450x550)</small>
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="preview_{{ $i }}"
                                        id="input_preview_{{ $i }}"
                                        onchange="image_preview(this,{{ $i }})">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="">left</label>
                                        <input {{ $required }} type="number" class="form-control"
                                            value="{{ $left }}" name="left_preview_{{ $i }}"
                                            value="440" id="left_preview_{{ $i }}" placeholder="left"
                                            onmousewheel="preview_drawing_area({{ $i }})"
                                            onkeyup="preview_drawing_area({{ $i }})">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">top</label>
                                        <input {{ $required }} type="number" class="form-control"
                                            value="{{ $top }}" name="top_preview_{{ $i }}"
                                            value="60" id="top_preview_{{ $i }}" placeholder="top"
                                            onmousewheel="preview_drawing_area({{ $i }})"
                                            onkeyup="preview_drawing_area({{ $i }})">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">height</label>
                                        <input {{ $required }} type="number" class="form-control"
                                            value="{{ $height }}" name="height_preview_{{ $i }}"
                                            value="400" id="height_preview_{{ $i }}" placeholder="height"
                                            onmousewheel="preview_drawing_area({{ $i }})"
                                            onkeyup="preview_drawing_area({{ $i }})">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">width</label>
                                        <input {{ $required }} type="number" class="form-control"
                                            value="{{ $width }}" name="width_preview_{{ $i }}"
                                            value="200" id="width_preview_{{ $i }}" placeholder="width"
                                            onmousewheel="preview_drawing_area({{ $i }})"
                                            onkeyup="preview_drawing_area({{ $i }})">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Preview Name</label>
                                    <input {{ $required }} type="text" class="form-control"
                                        value="{{ $name }}" name="name_preview_{{ $i }}"
                                        id="name_preview_{{ $i }}" placeholder="name">
                                </div>
                            </div>
                            <div class="col-md-6" id="image_preview_{{ $i }}">
                                <div style="position: relative">
                                    @if ($p != null)
                                        @php
                                            $prev = json_decode($p);
                                            $image = $prev ? $prev->image : '';
                                            $width = $prev ? $prev->width . 'px' : '';
                                            $height = $prev ? $prev->height . 'px' : '';
                                            $top = $prev ? $prev->top . 'px' : '';
                                            $left = $prev ? $prev->left . 'px' : '';
                                        @endphp
                                        <img src="{{ asset($image) }}" alt="" style="background: white;">
                                        <div class="drawing-area"
                                            style="width: {{ $width }};height:{{ $height }};top: {{ $top }}; left: {{ $left }};position: absolute; z-index: 10;border:1px dotted purple">
                                        </div>
                                    @else
                                        <div class="drawing-area"
                                            style="width: 200px;height:400px;top: 60px; left: 440px;position: absolute; z-index: 10;border:1px dotted purple;display:none">
                                        </div>
                                        <img src="" alt="" style="background: white;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                @endfor
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                الفيديوهات
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label for="video_provider">{{ trans('cruds.product.fields.video_provider') }}</label>
                    <select class="form-control {{ $errors->has('video_provider') ? 'is-invalid' : '' }}"
                        name="video_provider" id="video_provider">
                        <option value disabled
                            {{ old('video_provider', $mockup->video_provider) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\Product::VIDEO_PROVIDER_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('video_provider', $mockup->video_provider) === (string) $key ? 'selected' : '' }}>
                                {{ $label }}
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
                        name="video_link" id="video_link" value="{{ old('video_link', $mockup->video_link) }}">
                    @if ($errors->has('video_link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('video_link') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.video_link_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                أنواع المنتج
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required"
                                for="purchase_price">{{ trans('cruds.mockup.fields.purchase_price') }}</label>
                            <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}"
                                type="number" name="purchase_price" id="purchase_price"
                                value="{{ old('purchase_price', $mockup->purchase_price) }}" step="0.01" required>
                            @if ($errors->has('purchase_price'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('purchase_price') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.mockup.fields.purchase_price_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="colors">{{ trans('cruds.product.fields.colors') }}</label>
                            <select
                                class="form-control select2 color-var-select {{ $errors->has('colors') ? 'is-invalid' : '' }}"
                                name="colors[]" id="colors" multiple>
                                @foreach ($colors as $code => $entry)
                                    <option value="{{ $code }}" @if ($mockup->colors != null && in_array($code, json_decode($mockup->colors, true))) selected @endif>
                                        {{ $entry }} </option>
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
                            <select class="form-control select2 {{ $errors->has('attributes') ? 'is-invalid' : '' }}"
                                name="attributes[]" id="attributes" multiple>
                                @foreach ($attributes as $id => $entry)
                                    <option value="{{ $id }}"
                                        @if ($mockup->attributes != null && in_array($id, json_decode($mockup->attributes, true))) selected @endif> {{ $entry }}</option>
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
                            @foreach (json_decode($mockup->attribute_options) as $key => $option)
                                @php
                                    $attribute = \App\Models\Attribute::find($option->attribute_id);
                                @endphp
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="hidden" name="attribute_options[]"
                                                value="{{ $option->attribute_id }}">
                                            <input type="text" class="form-control"
                                                value="{{ $attribute->name ?? '' }}" placeholder="Choice Title" disabled>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control"
                                                name="attribute_options_{{ $option->attribute_id }}[]"
                                                placeholder="Enter choice values"
                                                value="{{ implode(',', $option->values) }}" data-role="tagsinput"
                                                onchange="update_sku()">
                                        </div> 
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="sku_combination_edit">
                            {{-- ajax call --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <label for="description">{{ trans('cruds.mockup.fields.description') }}</label>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                        id="description">{!! old('description', $mockup->description) !!}</textarea>
                    @if ($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.mockup.fields.description_helper') }}</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            get_sub_categories_by_category();
            update_sku();
        });
        $('#attributes').on('change', function() {
            $('#attribute_options').html(null);
            $.each($("#attributes option:selected"), function() {
                add_more_attributes_option($(this).val(), $(this).text());
            });
            update_sku();
        });

        function add_more_attributes_option(i, name) {
            $options = '<div class="row">';
            $options += '<div class="col-md-4">';
            $options += '<input type="hidden" name="attribute_options[]" value="' + i + '">';
            $options += '<input type="text" class="form-control" value="' + name + '" placeholder="Choice Title" readonly>';
            $options += '</div>';
            $options += '<div class="col-md-8">';
            $options += '<input type="text" class="form-control" name="attribute_options_' + i +
                '[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()">';
            $options += '</div>';
            $options += '</div>';
            $('#attribute_options').append($options);

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        $('#colors').on('change', function() {
            update_sku();
        }); 
        $('input[name="purchase_price"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em) {
            $(em).closest('.form-group').remove();
            update_sku();
        }

        function update_sku() {
            $.ajax({
                type: "POST",
                url: '{{ route('admin.mockups.sku_combination_edit') }}',
                data: $('#update_mockup').serialize(),
                success: function(data) {
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

        function get_sub_categories_by_category() {
            var category_id = $('#category_id').val();
            $.post('{{ route('admin.products.get_sub_categories_by_category') }}', {
                _token: '{{ csrf_token() }}',
                category_id: category_id
            }, function(data) {
                $('#sub_category_id').html(null);
                $('#sub_sub_category_id').html(null);

                for (var i = 0; i < data.length; i++) {
                    $('#sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                $("#sub_category_id > option").each(function() {
                    if(this.value == '{{$mockup->sub_category_id}}'){
                        $("#sub_category_id").val(this.value).change();
                    }
                }); 
                get_sub_sub_categories_by_category();
            });
        }

        function get_sub_sub_categories_by_category() {
            var sub_category_id = $('#sub_category_id').val();
            $.post('{{ route('admin.products.get_sub_sub_categories_by_subcategory') }}', {
                _token: '{{ csrf_token() }}',
                sub_category_id: sub_category_id
            }, function(data) {
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
                    if(this.value == '{{$mockup->sub_sub_category_id}}'){
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
@endsection
