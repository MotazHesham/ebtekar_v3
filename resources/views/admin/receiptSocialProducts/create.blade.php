@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.receiptSocialProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-social-products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
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
                <div class="form-group col-md-6">
                    <label>نوع المنتج</label>
                    <select class="form-control {{ $errors->has('product_type') ? 'is-invalid' : '' }}"
                        name="product_type" id="product_type">
                        <option value disabled {{ old('product_type', null) === null ? 'selected' : '' }}>
                            {{ __('global.pleaseSelect') }}</option>
                        @foreach (App\Models\ReceiptSocialProduct::PRODUCT_TYPE_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('product_type') === (string) $key ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('product_type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('product_type') }}
                        </div>
                    @endif 
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="name">{{ __('cruds.receiptSocialProduct.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptSocialProduct.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="price">{{ __('cruds.receiptSocialProduct.fields.price') }}</label>
                    <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="0.01" required>
                    @if($errors->has('price'))
                        <div class="invalid-feedback">
                            {{ $errors->first('price') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptSocialProduct.fields.price_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="quantity">{{ __('cruds.receiptSocialProduct.fields.quantity') }}</label>
                    <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', '') }}" step="1" required>
                    @if($errors->has('quantity'))
                        <div class="invalid-feedback">
                            {{ $errors->first('quantity') }}
                        </div>
                    @endif
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="commission">{{ __('cruds.receiptSocialProduct.fields.commission') }}</label>
                    <input class="form-control {{ $errors->has('commission') ? 'is-invalid' : '' }}" type="number" name="commission" id="commission" value="{{ old('commission', '') }}" step="0.01" required>
                    @if($errors->has('commission'))
                        <div class="invalid-feedback">
                            {{ $errors->first('commission') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptSocialProduct.fields.commission_helper') }}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="photos">{{ __('cruds.receiptSocialProduct.fields.photos') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}" id="photos-dropzone">
                </div>
                @if($errors->has('photos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photos') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.receiptSocialProduct.fields.photos_helper') }}</span>
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
    var uploadedPhotosMap = {}
Dropzone.options.photosDropzone = {
    url: '{{ route('admin.receipt-social-products.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.webp',
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
@if(isset($receiptSocialProduct) && $receiptSocialProduct->photos)
      var files = {!! json_encode($receiptSocialProduct->photos) !!}
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