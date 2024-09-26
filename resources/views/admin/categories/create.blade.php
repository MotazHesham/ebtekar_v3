@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="website_setting_id">{{ __('global.extra.website_setting_id') }}</label>
                <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}" name="website_setting_id" id="website_setting_id" required>
                    @foreach($websites as $id => $entry)
                        <option value="{{ $id }}" {{ old('website_setting_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('website_setting_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website_setting_id') }}
                    </div>
                @endif 
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.category.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="banner">{{ __('cruds.category.fields.banner') }}</label>
                <div class="needsclick dropzone {{ $errors->has('banner') ? 'is-invalid' : '' }}" id="banner-dropzone">
                </div>
                @if($errors->has('banner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banner') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.category.fields.banner_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="icon">{{ __('cruds.category.fields.icon') }}</label>
                <div class="needsclick dropzone {{ $errors->has('icon') ? 'is-invalid' : '' }}" id="icon-dropzone">
                </div>
                @if($errors->has('icon'))
                    <div class="invalid-feedback">
                        {{ $errors->first('icon') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.category.fields.icon_helper') }}</span>
            </div> 
            <div class="form-group">
                <label for="meta_title">{{ __('cruds.category.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', '') }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.category.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ __('cruds.category.fields.meta_description') }}</label>
                <input class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', '') }}">
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.category.fields.meta_description_helper') }}</span>
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
    Dropzone.options.bannerDropzone = {
    url: '{{ route('admin.categories.storeMedia') }}',
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
      $('form').find('input[name="banner"]').remove()
      $('form').append('<input type="hidden" name="banner" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="banner"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($category) && $category->banner)
      var file = {!! json_encode($category->banner) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="banner" value="' + file.file_name + '">')
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
    Dropzone.options.iconDropzone = {
    url: '{{ route('admin.categories.storeMedia') }}',
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
      $('form').find('input[name="icon"]').remove()
      $('form').append('<input type="hidden" name="icon" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="icon"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($category) && $category->icon)
      var file = {!! json_encode($category->icon) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="icon" value="' + file.file_name + '">')
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