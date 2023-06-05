@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.receiptCompany.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-companies.update", [$receiptCompany->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="client_name">{{ trans('cruds.receiptCompany.fields.client_name') }}</label>
                <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $receiptCompany->client_name) }}" required>
                @if($errors->has('client_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.client_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptCompany.fields.client_type') }}</label>
                <select class="form-control {{ $errors->has('client_type') ? 'is-invalid' : '' }}" name="client_type" id="client_type" required>
                    <option value disabled {{ old('client_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptCompany::CLIENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('client_type', $receiptCompany->client_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('client_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.client_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.receiptCompany.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $receiptCompany->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone_number_2">{{ trans('cruds.receiptCompany.fields.phone_number_2') }}</label>
                <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}" type="text" name="phone_number_2" id="phone_number_2" value="{{ old('phone_number_2', $receiptCompany->phone_number_2) }}">
                @if($errors->has('phone_number_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.phone_number_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deposit">{{ trans('cruds.receiptCompany.fields.deposit') }}</label>
                <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" type="number" name="deposit" id="deposit" value="{{ old('deposit', $receiptCompany->deposit) }}" step="0.01">
                @if($errors->has('deposit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.deposit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_cost">{{ trans('cruds.receiptCompany.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', $receiptCompany->total_cost) }}" step="0.01">
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deliver_date">{{ trans('cruds.receiptCompany.fields.deliver_date') }}</label>
                <input class="form-control date {{ $errors->has('deliver_date') ? 'is-invalid' : '' }}" type="text" name="deliver_date" id="deliver_date" value="{{ old('deliver_date', $receiptCompany->deliver_date) }}">
                @if($errors->has('deliver_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deliver_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.deliver_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_of_receiving_order">{{ trans('cruds.receiptCompany.fields.date_of_receiving_order') }}</label>
                <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order', $receiptCompany->date_of_receiving_order) }}">
                @if($errors->has('date_of_receiving_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_of_receiving_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.date_of_receiving_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_name">{{ trans('cruds.receiptCompany.fields.shipping_country_name') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_name') ? 'is-invalid' : '' }}" type="text" name="shipping_country_name" id="shipping_country_name" value="{{ old('shipping_country_name', $receiptCompany->shipping_country_name) }}" required>
                @if($errors->has('shipping_country_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.shipping_country_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_cost">{{ trans('cruds.receiptCompany.fields.shipping_country_cost') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_cost') ? 'is-invalid' : '' }}" type="number" name="shipping_country_cost" id="shipping_country_cost" value="{{ old('shipping_country_cost', $receiptCompany->shipping_country_cost) }}" step="0.01" required>
                @if($errors->has('shipping_country_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.shipping_country_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_address">{{ trans('cruds.receiptCompany.fields.shipping_address') }}</label>
                <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address" id="shipping_address" required>{{ old('shipping_address', $receiptCompany->shipping_address) }}</textarea>
                @if($errors->has('shipping_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.shipping_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.receiptCompany.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $receiptCompany->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.receiptCompany.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $receiptCompany->note) }}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="photos">{{ trans('cruds.receiptCompany.fields.photos') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photos') ? 'is-invalid' : '' }}" id="photos-dropzone">
                </div>
                @if($errors->has('photos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('photos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.photos_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptCompany.fields.delivery_status') }}</label>
                <select class="form-control {{ $errors->has('delivery_status') ? 'is-invalid' : '' }}" name="delivery_status" id="delivery_status" required>
                    <option value disabled {{ old('delivery_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptCompany::DELIVERY_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('delivery_status', $receiptCompany->delivery_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('delivery_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.delivery_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptCompany.fields.payment_status') }}</label>
                <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status" id="payment_status" required>
                    <option value disabled {{ old('payment_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptCompany::PAYMENT_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_status', $receiptCompany->payment_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.payment_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="staff_id">{{ trans('cruds.receiptCompany.fields.staff') }}</label>
                <select class="form-control select2 {{ $errors->has('staff') ? 'is-invalid' : '' }}" name="staff_id" id="staff_id" required>
                    @foreach($staff as $id => $entry)
                        <option value="{{ $id }}" {{ (old('staff_id') ? old('staff_id') : $receiptCompany->staff->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('staff'))
                    <div class="invalid-feedback">
                        {{ $errors->first('staff') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.staff_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_id">{{ trans('cruds.receiptCompany.fields.shipping_country') }}</label>
                <select class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}" name="shipping_country_id" id="shipping_country_id" required>
                    @foreach($shipping_countries as $id => $entry)
                        <option value="{{ $id }}" {{ (old('shipping_country_id') ? old('shipping_country_id') : $receiptCompany->shipping_country->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('shipping_country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptCompany.fields.shipping_country_helper') }}</span>
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
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.receipt-companies.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $receiptCompany->id ?? 0 }}');
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
    url: '{{ route('admin.receipt-companies.storeMedia') }}',
    maxFilesize: 5, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
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
@if(isset($receiptCompany) && $receiptCompany->photos)
      var files = {!! json_encode($receiptCompany->photos) !!}
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