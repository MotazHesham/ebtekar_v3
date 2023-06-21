@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.product.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', '') }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="unit_price">{{ trans('cruds.product.fields.unit_price') }}</label>
                    <input class="form-control {{ $errors->has('unit_price') ? 'is-invalid' : '' }}" type="number"
                        name="unit_price" id="unit_price" value="{{ old('unit_price', '') }}" step="0.01" required>
                    @if ($errors->has('unit_price'))
                        <div class="invalid-feedback">
                            {{ $errors->first('unit_price') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.unit_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="purchase_price">{{ trans('cruds.product.fields.purchase_price') }}</label>
                    <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number"
                        name="purchase_price" id="purchase_price" value="{{ old('purchase_price', '') }}" step="0.01"
                        required>
                    @if ($errors->has('purchase_price'))
                        <div class="invalid-feedback">
                            {{ $errors->first('purchase_price') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.purchase_price_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="slug">{{ trans('cruds.product.fields.slug') }}</label>
                    <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                        name="slug" id="slug" value="{{ old('slug', '') }}" required>
                    @if ($errors->has('slug'))
                        <div class="invalid-feedback">
                            {{ $errors->first('slug') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.slug_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="video_provider">{{ trans('cruds.product.fields.video_provider') }}</label>
                    <input class="form-control {{ $errors->has('video_provider') ? 'is-invalid' : '' }}" type="text"
                        name="video_provider" id="video_provider" value="{{ old('video_provider', '') }}">
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
                        name="video_link" id="video_link" value="{{ old('video_link', '') }}">
                    @if ($errors->has('video_link'))
                        <div class="invalid-feedback">
                            {{ $errors->first('video_link') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.video_link_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('cruds.product.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                        id="description">{!! old('description') !!}</textarea>
                    @if ($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                </div>
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
                <div class="form-group">
                    <label>{{ trans('cruds.product.fields.discount_type') }}</label>
                    <select class="form-control {{ $errors->has('discount_type') ? 'is-invalid' : '' }}"
                        name="discount_type" id="discount_type">
                        <option value disabled {{ old('discount_type', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\Product::DISCOUNT_TYPE_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('discount_type', 'flat') === (string) $key ? 'selected' : '' }}>{{ $label }}
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
                <div class="form-group">
                    <label for="discount">{{ trans('cruds.product.fields.discount') }}</label>
                    <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number"
                        name="discount" id="discount" value="{{ old('discount', '0') }}" step="0.01">
                    @if ($errors->has('discount'))
                        <div class="invalid-feedback">
                            {{ $errors->first('discount') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.discount_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="meta_title">{{ trans('cruds.product.fields.meta_title') }}</label>
                    <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text"
                        name="meta_title" id="meta_title" value="{{ old('meta_title', '') }}">
                    @if ($errors->has('meta_title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('meta_title') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.meta_title_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="meta_description">{{ trans('cruds.product.fields.meta_description') }}</label>
                    <textarea class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" name="meta_description"
                        id="meta_description">{{ old('meta_description') }}</textarea>
                    @if ($errors->has('meta_description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('meta_description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.meta_description_helper') }}</span>
                </div>
                <div class="form-group">
                    <div class="form-check {{ $errors->has('published') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="published" id="published" value="1"
                            required {{ old('published', 0) == 1 || old('published') === null ? 'checked' : '' }}>
                        <label class="required form-check-label"
                            for="published">{{ trans('cruds.product.fields.published') }}</label>
                    </div>
                    @if ($errors->has('published'))
                        <div class="invalid-feedback">
                            {{ $errors->first('published') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.published_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="user_id">{{ trans('cruds.product.fields.user') }}</label>
                    <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id"
                        id="user_id" required>
                        @foreach ($users as $id => $entry)
                            <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('user'))
                        <div class="invalid-feedback">
                            {{ $errors->first('user') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.user_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="category_id">{{ trans('cruds.product.fields.category') }}</label>
                    <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}"
                        name="category_id" id="category_id" required>
                        @foreach ($categories as $id => $entry)
                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
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
                    <label for="sub_category_id">{{ trans('cruds.product.fields.sub_category') }}</label>
                    <select class="form-control select2 {{ $errors->has('sub_category') ? 'is-invalid' : '' }}"
                        name="sub_category_id" id="sub_category_id">
                        @foreach ($sub_categories as $id => $entry)
                            <option value="{{ $id }}" {{ old('sub_category_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
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
                    <select class="form-control select2 {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}"
                        name="sub_sub_category_id" id="sub_sub_category_id">
                        @foreach ($sub_sub_categories as $id => $entry)
                            <option value="{{ $id }}"
                                {{ old('sub_sub_category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('sub_sub_category'))
                        <div class="invalid-feedback">
                            {{ $errors->first('sub_sub_category') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.sub_sub_category_helper') }}</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
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
@endsection
