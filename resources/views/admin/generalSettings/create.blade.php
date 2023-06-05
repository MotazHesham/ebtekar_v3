@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.generalSetting.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.general-settings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="logo">{{ trans('cruds.generalSetting.fields.logo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="logo-dropzone">
                </div>
                @if($errors->has('logo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.logo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="site_name">{{ trans('cruds.generalSetting.fields.site_name') }}</label>
                <input class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}" type="text" name="site_name" id="site_name" value="{{ old('site_name', '') }}" required>
                @if($errors->has('site_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.site_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.generalSetting.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address') }}</textarea>
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.generalSetting.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone_number">{{ trans('cruds.generalSetting.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}">
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.generalSetting.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="facebook">{{ trans('cruds.generalSetting.fields.facebook') }}</label>
                <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text" name="facebook" id="facebook" value="{{ old('facebook', '') }}">
                @if($errors->has('facebook'))
                    <div class="invalid-feedback">
                        {{ $errors->first('facebook') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.facebook_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="instagram">{{ trans('cruds.generalSetting.fields.instagram') }}</label>
                <input class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" type="text" name="instagram" id="instagram" value="{{ old('instagram', '') }}">
                @if($errors->has('instagram'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instagram') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.instagram_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="twitter">{{ trans('cruds.generalSetting.fields.twitter') }}</label>
                <input class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" type="text" name="twitter" id="twitter" value="{{ old('twitter', '') }}">
                @if($errors->has('twitter'))
                    <div class="invalid-feedback">
                        {{ $errors->first('twitter') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.twitter_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="telegram">{{ trans('cruds.generalSetting.fields.telegram') }}</label>
                <input class="form-control {{ $errors->has('telegram') ? 'is-invalid' : '' }}" type="text" name="telegram" id="telegram" value="{{ old('telegram', '') }}">
                @if($errors->has('telegram'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telegram') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.telegram_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="linkedin">{{ trans('cruds.generalSetting.fields.linkedin') }}</label>
                <input class="form-control {{ $errors->has('linkedin') ? 'is-invalid' : '' }}" type="text" name="linkedin" id="linkedin" value="{{ old('linkedin', '') }}">
                @if($errors->has('linkedin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('linkedin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.linkedin_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="whatsapp">{{ trans('cruds.generalSetting.fields.whatsapp') }}</label>
                <input class="form-control {{ $errors->has('whatsapp') ? 'is-invalid' : '' }}" type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', '') }}">
                @if($errors->has('whatsapp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('whatsapp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.whatsapp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="youtube">{{ trans('cruds.generalSetting.fields.youtube') }}</label>
                <input class="form-control {{ $errors->has('youtube') ? 'is-invalid' : '' }}" type="text" name="youtube" id="youtube" value="{{ old('youtube', '') }}">
                @if($errors->has('youtube'))
                    <div class="invalid-feedback">
                        {{ $errors->first('youtube') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.youtube_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="google_plus">{{ trans('cruds.generalSetting.fields.google_plus') }}</label>
                <input class="form-control {{ $errors->has('google_plus') ? 'is-invalid' : '' }}" type="text" name="google_plus" id="google_plus" value="{{ old('google_plus', '') }}">
                @if($errors->has('google_plus'))
                    <div class="invalid-feedback">
                        {{ $errors->first('google_plus') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.google_plus_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="welcome_message">{{ trans('cruds.generalSetting.fields.welcome_message') }}</label>
                <textarea class="form-control {{ $errors->has('welcome_message') ? 'is-invalid' : '' }}" name="welcome_message" id="welcome_message">{{ old('welcome_message') }}</textarea>
                @if($errors->has('welcome_message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('welcome_message') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.welcome_message_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photos">{{ trans('cruds.generalSetting.fields.photos') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}" id="photos-dropzone">
                </div>
                @if($errors->has('photos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.photos_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_instructions">{{ trans('cruds.generalSetting.fields.video_instructions') }}</label>
                <input class="form-control {{ $errors->has('video_instructions') ? 'is-invalid' : '' }}" type="text" name="video_instructions" id="video_instructions" value="{{ old('video_instructions', '') }}">
                @if($errors->has('video_instructions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_instructions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.video_instructions_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.generalSetting.fields.delivery_system') }}</label>
                <select class="form-control {{ $errors->has('delivery_system') ? 'is-invalid' : '' }}" name="delivery_system" id="delivery_system">
                    <option value disabled {{ old('delivery_system', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\GeneralSetting::DELIVERY_SYSTEM_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('delivery_system', 'ebtekar') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('delivery_system'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_system') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.delivery_system_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="designer_id">{{ trans('cruds.generalSetting.fields.designer') }}</label>
                <select class="form-control select2 {{ $errors->has('designer') ? 'is-invalid' : '' }}" name="designer_id" id="designer_id">
                    @foreach($designers as $id => $entry)
                        <option value="{{ $id }}" {{ old('designer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('designer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('designer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.designer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="preparer_id">{{ trans('cruds.generalSetting.fields.preparer') }}</label>
                <select class="form-control select2 {{ $errors->has('preparer') ? 'is-invalid' : '' }}" name="preparer_id" id="preparer_id">
                    @foreach($preparers as $id => $entry)
                        <option value="{{ $id }}" {{ old('preparer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('preparer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preparer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.preparer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="manufacturer_id">{{ trans('cruds.generalSetting.fields.manufacturer') }}</label>
                <select class="form-control select2 {{ $errors->has('manufacturer') ? 'is-invalid' : '' }}" name="manufacturer_id" id="manufacturer_id">
                    @foreach($manufacturers as $id => $entry)
                        <option value="{{ $id }}" {{ old('manufacturer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('manufacturer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('manufacturer') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.manufacturer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shipment_id">{{ trans('cruds.generalSetting.fields.shipment') }}</label>
                <select class="form-control select2 {{ $errors->has('shipment') ? 'is-invalid' : '' }}" name="shipment_id" id="shipment_id">
                    @foreach($shipments as $id => $entry)
                        <option value="{{ $id }}" {{ old('shipment_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('shipment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.generalSetting.fields.shipment_helper') }}</span>
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
    Dropzone.options.logoDropzone = {
    url: '{{ route('admin.general-settings.storeMedia') }}',
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
    success: function (file, response) {
      $('form').find('input[name="logo"]').remove()
      $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="logo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($generalSetting) && $generalSetting->logo)
      var file = {!! json_encode($generalSetting->logo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
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
    url: '{{ route('admin.general-settings.storeMedia') }}',
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
    success: function (file, response) {
      $('form').append('<input type="hidden" name="photos[]" value="' + response.name + '">')
      uploadedPhotosMap[file.name] = response.name
    },
    removedfile: function (file) {
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
    init: function () {
@if(isset($generalSetting) && $generalSetting->photos)
      var files = {!! json_encode($generalSetting->photos) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photos[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
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