@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.excelFile.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.excel-files.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>{{ trans('cruds.excelFile.fields.type') }}</label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                        <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\ExcelFile::TYPE_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('type', 'social_delivery') === (string) $key ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.excelFile.fields.type_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="uploaded_file">{{ trans('cruds.excelFile.fields.uploaded_file') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('uploaded_file') ? 'is-invalid' : '' }}"
                        id="uploaded_file-dropzone">
                    </div>
                    @if ($errors->has('uploaded_file'))
                        <div class="invalid-feedback">
                            {{ $errors->first('uploaded_file') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.excelFile.fields.uploaded_file_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="result_file">{{ trans('cruds.excelFile.fields.result_file') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('result_file') ? 'is-invalid' : '' }}"
                        id="result_file-dropzone">
                    </div>
                    @if ($errors->has('result_file'))
                        <div class="invalid-feedback">
                            {{ $errors->first('result_file') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.excelFile.fields.result_file_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="required" for="results">{{ trans('cruds.excelFile.fields.results') }}</label>
                    <textarea class="form-control {{ $errors->has('results') ? 'is-invalid' : '' }}" name="results" id="results"
                        required>{{ old('results') }}</textarea>
                    @if ($errors->has('results'))
                        <div class="invalid-feedback">
                            {{ $errors->first('results') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.excelFile.fields.results_helper') }}</span>
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
        Dropzone.options.uploadedFileDropzone = {
            url: '{{ route('admin.excel-files.storeMedia') }}',
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
                $('form').find('input[name="uploaded_file"]').remove()
                $('form').append('<input type="hidden" name="uploaded_file" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="uploaded_file"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($excelFile) && $excelFile->uploaded_file)
                    var file = {!! json_encode($excelFile->uploaded_file) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="uploaded_file" value="' + file.file_name + '">')
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
        Dropzone.options.resultFileDropzone = {
            url: '{{ route('admin.excel-files.storeMedia') }}',
            maxFilesize: 4, // MB
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 4
            },
            success: function(file, response) {
                $('form').find('input[name="result_file"]').remove()
                $('form').append('<input type="hidden" name="result_file" value="' + response.name + '">')
            },
            removedfile: function(file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="result_file"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function() {
                @if (isset($excelFile) && $excelFile->result_file)
                    var file = {!! json_encode($excelFile->result_file) !!}
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="result_file" value="' + file.file_name + '">')
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
