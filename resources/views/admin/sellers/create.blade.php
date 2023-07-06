@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.seller.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.sellers.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" id="name" value="{{ old('name', '') }}" required>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                            name="email" id="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">{{ trans('cruds.user.fields.address') }}</label>
                        <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text"
                            name="address" id="address" value="{{ old('address', '') }}">
                        @if ($errors->has('address'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.address_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone_number" class="required">{{ trans('cruds.user.fields.phone_number') }}</label>
                        <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text"
                            name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}">
                        @if ($errors->has('phone_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.phone_number_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                            name="password" id="password" required>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required">{{ trans('cruds.seller.fields.seller_type') }}</label>
                        <select class="form-control {{ $errors->has('seller_type') ? 'is-invalid' : '' }}"
                            name="seller_type" id="seller_type" required>
                            <option value disabled {{ old('seller_type', null) === null ? 'selected' : '' }}>
                                {{ trans('global.pleaseSelect') }}</option>
                            @foreach (App\Models\Seller::SELLER_TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('seller_type', 'seller') === (string) $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('seller_type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('seller_type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.seller_type_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="discount">{{ trans('cruds.seller.fields.discount') }}</label>
                        <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number"
                            name="discount" id="discount" value="{{ old('discount', '0') }}" step="0.01">
                        @if ($errors->has('discount'))
                            <div class="invalid-feedback">
                                {{ $errors->first('discount') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.discount_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="discount_code">{{ trans('cruds.seller.fields.discount_code') }}</label>
                        <input class="form-control {{ $errors->has('discount_code') ? 'is-invalid' : '' }}" type="text"
                            name="discount_code" id="discount_code" value="{{ old('discount_code', '') }}">
                        @if ($errors->has('discount_code'))
                            <div class="invalid-feedback">
                                {{ $errors->first('discount_code') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.discount_code_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="order_out_website">{{ trans('cruds.seller.fields.order_out_website') }}</label>
                        <input class="form-control {{ $errors->has('order_out_website') ? 'is-invalid' : '' }}"
                            type="number" name="order_out_website" id="order_out_website"
                            value="{{ old('order_out_website', '0') }}" step="1">
                        @if ($errors->has('order_out_website'))
                            <div class="invalid-feedback">
                                {{ $errors->first('order_out_website') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.order_out_website_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="qualification">{{ trans('cruds.seller.fields.qualification') }}</label>
                        <input class="form-control {{ $errors->has('qualification') ? 'is-invalid' : '' }}" type="text"
                            name="qualification" id="qualification" value="{{ old('qualification', '') }}">
                        @if ($errors->has('qualification'))
                            <div class="invalid-feedback">
                                {{ $errors->first('qualification') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.qualification_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="social_name">{{ trans('cruds.seller.fields.social_name') }}</label>
                        <input class="form-control {{ $errors->has('social_name') ? 'is-invalid' : '' }}" type="text"
                            name="social_name" id="social_name" value="{{ old('social_name', '') }}" required>
                        @if ($errors->has('social_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('social_name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.social_name_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="social_link">{{ trans('cruds.seller.fields.social_link') }}</label>
                        <input class="form-control {{ $errors->has('social_link') ? 'is-invalid' : '' }}" type="text"
                            name="social_link" id="social_link" value="{{ old('social_link', '') }}" required>
                        @if ($errors->has('social_link'))
                            <div class="invalid-feedback">
                                {{ $errors->first('social_link') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.social_link_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="photo">{{ trans('cruds.user.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                            id="photo-dropzone">
                        </div>
                        @if ($errors->has('photo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('photo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.photo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="identity_back">{{ trans('cruds.seller.fields.identity_back') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('identity_back') ? 'is-invalid' : '' }}"
                            id="identity_back-dropzone">
                        </div>
                        @if ($errors->has('identity_back'))
                            <div class="invalid-feedback">
                                {{ $errors->first('identity_back') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.identity_back_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="identity_front">{{ trans('cruds.seller.fields.identity_front') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('identity_front') ? 'is-invalid' : '' }}"
                            id="identity_front-dropzone">
                        </div>
                        @if ($errors->has('identity_front'))
                            <div class="invalid-feedback">
                                {{ $errors->first('identity_front') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.seller.fields.identity_front_helper') }}</span>
                    </div>
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
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.sellers.storeMedia') }}',
            maxFilesize: 5, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5,
                width: 4096,
                height: 4096
            },
            success: function(file, response) {
                $('form').find('input[name="photo"]').remove()
                $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($seller->user) && $seller->user->photo)
                    var file = {!! json_encode($seller->user->photo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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
        Dropzone.options.identityBackDropzone = {
            url: '{{ route('admin.sellers.storeMedia') }}',
            maxFilesize: 4, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
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
                $('form').find('input[name="identity_back"]').remove()
                $('form').append('<input type="hidden" name="identity_back" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="identity_back"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($seller) && $seller->identity_back)
                    var file = {!! json_encode($seller->identity_back) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="identity_back" value="' + file.file_name + '">')
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
        Dropzone.options.identityFrontDropzone = {
            url: '{{ route('admin.sellers.storeMedia') }}',
            maxFilesize: 4, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
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
                $('form').find('input[name="identity_front"]').remove()
                $('form').append('<input type="hidden" name="identity_front" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="identity_front"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($seller) && $seller->identity_front)
                    var file = {!! json_encode($seller->identity_front) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="identity_front" value="' + file.file_name + '">')
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
