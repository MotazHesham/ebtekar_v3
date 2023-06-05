@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.mockup.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.mockups.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.mockup.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.mockup.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="preview_1">{{ trans('cruds.mockup.fields.preview_1') }}</label>
                <div class="needsclick dropzone {{ $errors->has('preview_1') ? 'is-invalid' : '' }}" id="preview_1-dropzone">
                </div>
                @if($errors->has('preview_1'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preview_1') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.preview_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="preview_2">{{ trans('cruds.mockup.fields.preview_2') }}</label>
                <div class="needsclick dropzone {{ $errors->has('preview_2') ? 'is-invalid' : '' }}" id="preview_2-dropzone">
                </div>
                @if($errors->has('preview_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preview_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.preview_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="preview_3">{{ trans('cruds.mockup.fields.preview_3') }}</label>
                <div class="needsclick dropzone {{ $errors->has('preview_3') ? 'is-invalid' : '' }}" id="preview_3-dropzone">
                </div>
                @if($errors->has('preview_3'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preview_3') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.preview_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_provider">{{ trans('cruds.mockup.fields.video_provider') }}</label>
                <input class="form-control {{ $errors->has('video_provider') ? 'is-invalid' : '' }}" type="text" name="video_provider" id="video_provider" value="{{ old('video_provider', '') }}">
                @if($errors->has('video_provider'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_provider') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.video_provider_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="video_link">{{ trans('cruds.mockup.fields.video_link') }}</label>
                <input class="form-control {{ $errors->has('video_link') ? 'is-invalid' : '' }}" type="text" name="video_link" id="video_link" value="{{ old('video_link', '') }}">
                @if($errors->has('video_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('video_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.video_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="purchase_price">{{ trans('cruds.mockup.fields.purchase_price') }}</label>
                <input class="form-control {{ $errors->has('purchase_price') ? 'is-invalid' : '' }}" type="number" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', '') }}" step="0.01" required>
                @if($errors->has('purchase_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('purchase_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.purchase_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="category_id">{{ trans('cruds.mockup.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sub_category_id">{{ trans('cruds.mockup.fields.sub_category') }}</label>
                <select class="form-control select2 {{ $errors->has('sub_category') ? 'is-invalid' : '' }}" name="sub_category_id" id="sub_category_id" required>
                    @foreach($sub_categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('sub_category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sub_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sub_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.sub_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sub_sub_category_id">{{ trans('cruds.mockup.fields.sub_sub_category') }}</label>
                <select class="form-control select2 {{ $errors->has('sub_sub_category') ? 'is-invalid' : '' }}" name="sub_sub_category_id" id="sub_sub_category_id">
                    @foreach($sub_sub_categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('sub_sub_category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sub_sub_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sub_sub_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mockup.fields.sub_sub_category_helper') }}</span>
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
    Dropzone.options.preview1Dropzone = {
    url: '{{ route('admin.mockups.storeMedia') }}',
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
    success: function (file, response) {
      $('form').find('input[name="preview_1"]').remove()
      $('form').append('<input type="hidden" name="preview_1" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="preview_1"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mockup) && $mockup->preview_1)
      var file = {!! json_encode($mockup->preview_1) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="preview_1" value="' + file.file_name + '">')
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
    Dropzone.options.preview2Dropzone = {
    url: '{{ route('admin.mockups.storeMedia') }}',
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
    success: function (file, response) {
      $('form').find('input[name="preview_2"]').remove()
      $('form').append('<input type="hidden" name="preview_2" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="preview_2"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mockup) && $mockup->preview_2)
      var file = {!! json_encode($mockup->preview_2) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="preview_2" value="' + file.file_name + '">')
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
    Dropzone.options.preview3Dropzone = {
    url: '{{ route('admin.mockups.storeMedia') }}',
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
    success: function (file, response) {
      $('form').find('input[name="preview_3"]').remove()
      $('form').append('<input type="hidden" name="preview_3" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="preview_3"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($mockup) && $mockup->preview_3)
      var file = {!! json_encode($mockup->preview_3) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="preview_3" value="' + file.file_name + '">')
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