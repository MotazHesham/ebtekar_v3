@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.designer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.designers.update", [$designer->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="user_id" value="{{ $designer->user->id }}">
            <div class="row">
                <div class="form-group col-md-4">
                    <label class="required" for="store_name">{{ __('cruds.designer.fields.store_name') }}</label>
                    <input class="form-control {{ $errors->has('store_name') ? 'is-invalid' : '' }}" type="text" name="store_name" id="store_name" value="{{ old('store_name', $designer->store_name) }}" required>
                    @if($errors->has('store_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('store_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.designer.fields.store_name_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="name">{{ __('cruds.user.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                        name="name" id="name" value="{{ old('name', $designer->user->name) }}" required>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="email">{{ __('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                        name="email" id="email" value="{{ old('email', $designer->user->email) }}" required>
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.email_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="phone_number">{{ __('cruds.user.fields.phone_number') }}</label>
                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text"
                        name="phone_number" id="phone_number"
                        value="{{ old('phone_number', $designer->user->phone_number) }}">
                    @if ($errors->has('phone_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.phone_number_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="address">{{ __('cruds.user.fields.address') }}</label>
                    <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text"
                        name="address" id="address" value="{{ old('address', $designer->user->address) }}">
                    @if ($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.address_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="password">{{ __('cruds.user.fields.password') }}</label>
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                        name="password" id="password">
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.password_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="photo">{{ __('cruds.user.fields.photo') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                        id="photo-dropzone">
                    </div>
                    @if ($errors->has('photo'))
                        <div class="invalid-feedback">
                            {{ $errors->first('photo') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.user.fields.photo_helper') }}</span>
                </div>
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
    Dropzone.options.photoDropzone = {
        url: '{{ route('admin.designers.storeMedia') }}',
        maxFilesize: 5, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
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
            @if (isset($designer->user) && $designer->user->photo)
                var file = {!! json_encode($designer->user->photo) !!}
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
@endsection