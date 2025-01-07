@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.qrs.generate') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="" class="required">Text</label>
                            <textarea class="form-control" name="text" id="" cols="30" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="required" for="">Size</label>
                            <input type="number" class="form-control" name="size" required id="" value="250" min="1">
                        </div>
                        <div class="form-group">
                            <label for="">Color</label>
                            <input type="color" class="form-control" value="#FFFFFF" name="color" id="">
                        </div>
                        <div class="form-group">
                            <label for="photo">صورة</label>
                            <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                                id="photo-dropzone">
                            </div>  
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @isset($generated)
                        @production
                            <img src="data:image/png;base64, {!! $qr !!} ">
                        @else
                            {!! $qr !!}
                        @endproduction
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.socials.storeMedia') }}',
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
                @if (isset($social) && $social->photo)
                    var file = {!! json_encode($social->photo) !!}
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
