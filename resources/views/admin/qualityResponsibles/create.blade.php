@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.qualityResponsible.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.quality-responsibles.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.qualityResponsible.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.qualityResponsible.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="photo">{{ __('cruds.qualityResponsible.fields.photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                </div>
                @if($errors->has('photo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photo') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.qualityResponsible.fields.photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ __('cruds.qualityResponsible.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.qualityResponsible.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="wts_phone">{{ __('cruds.qualityResponsible.fields.wts_phone') }}</label>
                <input class="form-control {{ $errors->has('wts_phone') ? 'is-invalid' : '' }}" type="text" name="wts_phone" id="wts_phone" value="{{ old('wts_phone', '') }}" required>
                @if($errors->has('wts_phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('wts_phone') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.qualityResponsible.fields.wts_phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country_code">{{ __('cruds.qualityResponsible.fields.country_code') }}</label>
                <input class="form-control {{ $errors->has('country_code') ? 'is-invalid' : '' }}" type="text" name="country_code" id="country_code" value="{{ old('country_code', '') }}" required>
                @if($errors->has('country_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country_code') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.qualityResponsible.fields.country_code_helper') }}</span>
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
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.quality-responsibles.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($qualityResponsible) && $qualityResponsible->photo)
      var file = {!! json_encode($qualityResponsible->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
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
@endsection