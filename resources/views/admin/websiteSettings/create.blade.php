@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('global.create') }} {{ __('cruds.websiteSetting.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.website-settings.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="required" for="logo">{{ __('cruds.websiteSetting.fields.logo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="logo-dropzone">
                        </div>
                        @if ($errors->has('logo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('logo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.logo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-8">
                        <label for="photos">{{ __('cruds.websiteSetting.fields.photos') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}"
                            id="photos-dropzone">
                        </div>
                        @if ($errors->has('photos'))
                            <div class="invalid-feedback">
                                {{ $errors->first('photos') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.photos_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required"
                            for="site_name">{{ __('cruds.websiteSetting.fields.site_name') }}</label>
                        <input class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}" type="text"
                            name="site_name" id="site_name" value="{{ old('site_name') }}" required>
                        @if ($errors->has('site_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('site_name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.site_name_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required" for="domains">{{ __('cruds.websiteSetting.fields.domains') }}</label>
                        <input class="form-control {{ $errors->has('domains') ? 'is-invalid' : '' }}" type="text"
                            name="domains[]" id="domains" 
                            placeholder="add domains (example.com)..." data-role="tagsinput" required>
                        @if ($errors->has('domains'))
                            <div class="invalid-feedback">
                                {{ $errors->first('domains') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.domains_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required"
                            for="keywords_seo">{{ __('cruds.websiteSetting.fields.keywords_seo') }}</label>
                        <input class="form-control {{ $errors->has('keywords_seo') ? 'is-invalid' : '' }}" type="text"
                            name="keywords_seo[]" id="keywords_seo" placeholder="add tags ..." data-role="tagsinput"
                            required>
                        @if ($errors->has('keywords_seo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('keywords_seo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.keywords_seo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required"
                            for="author_seo">{{ __('cruds.websiteSetting.fields.author_seo') }}</label>
                        <input class="form-control {{ $errors->has('author_seo') ? 'is-invalid' : '' }}" type="text"
                            name="author_seo" id="author_seo" value="{{ old('author_seo', '') }}" required>
                        @if ($errors->has('author_seo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('author_seo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.author_seo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required"
                            for="sitemap_link_seo">{{ __('cruds.websiteSetting.fields.sitemap_link_seo') }}</label>
                        <input class="form-control {{ $errors->has('sitemap_link_seo') ? 'is-invalid' : '' }}"
                            type="text" name="sitemap_link_seo" id="sitemap_link_seo"
                            value="{{ old('sitemap_link_seo', '') }}" required>
                        @if ($errors->has('sitemap_link_seo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sitemap_link_seo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.sitemap_link_seo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required"
                            for="description_seo">{{ __('cruds.websiteSetting.fields.description_seo') }}</label>
                        <textarea class="form-control {{ $errors->has('description_seo') ? 'is-invalid' : '' }}" name="description_seo"
                            id="description_seo" required>{{ old('description_seo') }}</textarea>
                        @if ($errors->has('description_seo'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description_seo') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.description_seo_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">{{ __('cruds.websiteSetting.fields.address') }}</label>
                        <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address') }}</textarea>
                        @if ($errors->has('address'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.address_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="description">{{ __('cruds.websiteSetting.fields.description') }}</label>
                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                            id="description">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.description_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="phone_number">{{ __('cruds.websiteSetting.fields.phone_number') }}</label>
                        <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text"
                            name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
                        @if ($errors->has('phone_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.phone_number_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">{{ __('cruds.websiteSetting.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                            name="email" id="email" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.email_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="facebook">{{ __('cruds.websiteSetting.fields.facebook') }}</label>
                        <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text"
                            name="facebook" id="facebook" value="{{ old('facebook') }}">
                        @if ($errors->has('facebook'))
                            <div class="invalid-feedback">
                                {{ $errors->first('facebook') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.facebook_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="instagram">{{ __('cruds.websiteSetting.fields.instagram') }}</label>
                        <input class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" type="text"
                            name="instagram" id="instagram" value="{{ old('instagram') }}">
                        @if ($errors->has('instagram'))
                            <div class="invalid-feedback">
                                {{ $errors->first('instagram') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.instagram_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="twitter">{{ __('cruds.websiteSetting.fields.twitter') }}</label>
                        <input class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" type="text"
                            name="twitter" id="twitter" value="{{ old('twitter') }}">
                        @if ($errors->has('twitter'))
                            <div class="invalid-feedback">
                                {{ $errors->first('twitter') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.twitter_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="telegram">{{ __('cruds.websiteSetting.fields.telegram') }}</label>
                        <input class="form-control {{ $errors->has('telegram') ? 'is-invalid' : '' }}" type="text"
                            name="telegram" id="telegram" value="{{ old('telegram') }}">
                        @if ($errors->has('telegram'))
                            <div class="invalid-feedback">
                                {{ $errors->first('telegram') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.telegram_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="linkedin">{{ __('cruds.websiteSetting.fields.linkedin') }}</label>
                        <input class="form-control {{ $errors->has('linkedin') ? 'is-invalid' : '' }}" type="text"
                            name="linkedin" id="linkedin" value="{{ old('linkedin') }}">
                        @if ($errors->has('linkedin'))
                            <div class="invalid-feedback">
                                {{ $errors->first('linkedin') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.linkedin_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="whatsapp">{{ __('cruds.websiteSetting.fields.whatsapp') }}</label>
                        <input class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" type="text"
                            name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}">
                        @if ($errors->has('whatsapp'))
                            <div class="invalid-feedback">
                                {{ $errors->first('whatsapp') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.whatsapp_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="youtube">{{ __('cruds.websiteSetting.fields.youtube') }}</label>
                        <input class="form-control {{ $errors->has('youtube') ? 'is-invalid' : '' }}" type="text"
                            name="youtube" id="youtube" value="{{ old('youtube') }}">
                        @if ($errors->has('youtube'))
                            <div class="invalid-feedback">
                                {{ $errors->first('youtube') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.youtube_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="google_plus">{{ __('cruds.websiteSetting.fields.google_plus') }}</label>
                        <input class="form-control {{ $errors->has('google_plus') ? 'is-invalid' : '' }}" type="text"
                            name="google_plus" id="google_plus" value="{{ old('google_plus') }}">
                        @if ($errors->has('google_plus'))
                            <div class="invalid-feedback">
                                {{ $errors->first('google_plus') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.google_plus_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="welcome_message">{{ __('cruds.websiteSetting.fields.welcome_message') }}</label>
                        <textarea class="form-control {{ $errors->has('welcome_message') ? 'is-invalid' : '' }}" name="welcome_message"
                            id="welcome_message">{{ old('welcome_message') }}</textarea>
                        @if ($errors->has('welcome_message'))
                            <div class="invalid-feedback">
                                {{ $errors->first('welcome_message') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.welcome_message_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label
                            for="video_instructions">{{ __('cruds.websiteSetting.fields.video_instructions') }}</label>
                        <input class="form-control {{ $errors->has('video_instructions') ? 'is-invalid' : '' }}"
                            type="text" name="video_instructions" id="video_instructions"
                            value="{{ old('video_instructions') }}">
                        @if ($errors->has('video_instructions'))
                            <div class="invalid-feedback">
                                {{ $errors->first('video_instructions') }}
                            </div>
                        @endif
                        <span
                            class="help-block">{{ __('cruds.websiteSetting.fields.video_instructions_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label>{{ __('cruds.websiteSetting.fields.delivery_system') }}</label>
                        <select class="form-control {{ $errors->has('delivery_system') ? 'is-invalid' : '' }}"
                            name="delivery_system" id="delivery_system">
                            <option value disabled {{ old('delivery_system', null) === null ? 'selected' : '' }}>
                                {{ __('global.pleaseSelect') }}</option>
                            @foreach (App\Models\WebsiteSetting::DELIVERY_SYSTEM_SELECT as $key => $label)
                                <option value="{{ $key }}"
                                    {{ old('delivery_system') === (string) $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('delivery_system'))
                            <div class="invalid-feedback">
                                {{ $errors->first('delivery_system') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.delivery_system_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="designer_id">{{ __('cruds.websiteSetting.fields.designer') }}</label>
                        <select class="form-control select2 {{ $errors->has('designer') ? 'is-invalid' : '' }}"
                            name="designer_id" id="designer_id">
                            @foreach ($designers as $id => $entry)
                                <option value="{{ $id }}" {{ old('designer_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('designer'))
                            <div class="invalid-feedback">
                                {{ $errors->first('designer') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.designer_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="preparer_id">{{ __('cruds.websiteSetting.fields.preparer') }}</label>
                        <select class="form-control select2 {{ $errors->has('preparer') ? 'is-invalid' : '' }}"
                            name="preparer_id" id="preparer_id">
                            @foreach ($preparers as $id => $entry)
                                <option value="{{ $id }}" {{ old('preparer_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('preparer'))
                            <div class="invalid-feedback">
                                {{ $errors->first('preparer') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.preparer_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="manufacturer_id">{{ __('cruds.websiteSetting.fields.manufacturer') }}</label>
                        <select class="form-control select2 {{ $errors->has('manufacturer') ? 'is-invalid' : '' }}"
                            name="manufacturer_id" id="manufacturer_id">
                            @foreach ($manufacturers as $id => $entry)
                                <option value="{{ $id }}"
                                    {{ old('manufacturer_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('manufacturer'))
                            <div class="invalid-feedback">
                                {{ $errors->first('manufacturer') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.manufacturer_helper') }}</span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="shipmenter_id">{{ __('cruds.websiteSetting.fields.shipment') }}</label>
                        <select class="form-control select2 {{ $errors->has('shipment') ? 'is-invalid' : '' }}"
                            name="shipmenter_id" id="shipmenter_id">
                            @foreach ($shipments as $id => $entry)
                                <option value="{{ $id }}" {{ old('shipmenter_id') == $id ? 'selected' : '' }}>
                                    {{ $entry }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('shipment'))
                            <div class="invalid-feedback">
                                {{ $errors->first('shipment') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.websiteSetting.fields.shipment_helper') }}</span>
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
@endsection

@section('scripts')
    <script>
        Dropzone.options.logoDropzone = {
            url: '{{ route('admin.website-settings.storeMedia') }}',
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
                $('form').find('input[name="logo"]').remove()
                $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="logo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($websiteSetting) && $websiteSetting->logo)
                    var file = {!! json_encode($websiteSetting->logo) !!}
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
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
        var uploadedPhotosMap = {}
        Dropzone.options.photosDropzone = {
            url: '{{ route('admin.website-settings.storeMedia') }}',
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
                @if (isset($websiteSetting) && $websiteSetting->photos)
                    var files = {!! json_encode($websiteSetting->photos) !!}
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
@endsection
