@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ trans('global.my_profile') }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("profile.updateProfile") }}">
                    @csrf
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="required" for="phone_number">{{ trans('cruds.user.fields.phone_number') }}</label>
                                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" required>
                                @if($errors->has('phone_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('phone_number') }}
                                    </div>
                                @endif
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="photo">{{ trans('cruds.user.fields.photo') }}</label>
                                <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone">
                                </div>
                                @if($errors->has('photo'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('photo') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.photo_helper') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ trans('global.change_password') }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("profile.password.update") }}">
                    @csrf
                    <div class="form-group">
                        <label class="required" for="email">New {{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="title">Repeat New {{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">  

    <div class="col-md-12 ">
        <div class="card">
            <div class="card-header">
                @if(auth()->user()->wasla_token == null)
                    <h5>{{__('تسجبل الدخول لوصلة')}}</h5>
                @else 
                    <h5>{{__('بيانات حساب وصلة')}}</h5>
                @endif
            </div> 
            
            <form action="{{route('profile.wasla_login')}}" method="POST">
                @csrf   
                <div class="card-body">
                    <div class="row">
                        
                        @if(auth()->user()->wasla_token == null)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="{{ trans('cruds.user.fields.email') }}" id="email"> 
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="{{ trans('cruds.user.fields.password') }}" id="password"> 
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="تسجيل الدخول" class="btn btn-info" name="" id=""> 
                                </div>
                            </div>
                        @else
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ $data['logo'] ?? '' }}" alt="" width="80" height="80" style="border-radius: 50%;box-shadow: 1px 1px 18px #b9b0b0;">
                                        <br><br>
                                        <a href="{{ route('profile.wasla_logout') }}" class="btn btn-danger btn-rounded">تسجيل الخروج</a>
                                    </div>
                                    <div class="col-md-8">
                                        <span class="badge badge-info">اسم الشركة</span> 
                                        {{$data['company_name'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-info">البريد الألكتروني</span> 
                                        {{$data['email'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-info">المجال</span> 
                                        {{$data['work_type'] ?? ''}} 

                                        <br> <br>
                                        
                                        <span class="badge badge-info">رقم الهاتف</span> 
                                        {{$data['phone'] ?? ''}} 

                                        <br> <br>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <span class="badge badge-info">التخفيض</span> 
                                                <br> 
                                                {{$data['discount'] ?? ''}} 
                                            </div>
                                            <div class="col-md-4"> 
                                                <span class="badge badge-info">سعر المرتجعات</span> 
                                                <br> 
                                                {{$data['refund_price'] ?? ''}} 
                                            </div>
                                            <div class="col-md-4">
                                                <span class="badge badge-info">سعر المهمة</span> 
                                                <br> 
                                                {{$data['mission_price'] ?? ''}} 
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="height: 400px; overflow-x: hidden; overflow-y: scroll;"> 
                                <h5>سعر توصيل المحافظات</h5>
                                <table class="mt-3 table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>الأسم</th>
                                            <th>السعر</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data)
                                        @if($data['countries'])
                                            @foreach($data['countries'] as $key => $row) 
                                                <tr>
                                                    <td>{{$row['name']}}</td>
                                                    <td>{{$row['cost']}}</td> 
                                                </tr>
                                            @endforeach
                                        @endif
                                        @endif
                                    </tbody>
                                </table>  
                            </div>
                        @endif
                    </div>
                </div> 
            </form> 

        </div>
    </div>

</div>
@endsection
@section('scripts')
    <script>
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.users.storeMedia') }}',
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
                @if (isset($user) && $user->photo)
                    var file = {!! json_encode($user->photo) !!}
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