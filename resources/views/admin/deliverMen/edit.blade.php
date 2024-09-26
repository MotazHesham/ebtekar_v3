@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.deliverMan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.deliver-men.update", [$deliverMan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="user_id" value="{{$deliverMan->user->id}}"> 
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                    id="name" value="{{ old('name', $deliverMan->user->name) }}" required>
                @if ($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ __('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                    name="email" id="email" value="{{ old('email', $deliverMan->user->email) }}" required>
                @if ($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone_number">{{ __('cruds.user.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text"
                    name="phone_number" id="phone_number" value="{{ old('phone_number', $deliverMan->user->phone_number) }}">
                @if ($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.user.fields.phone_number_helper') }}</span>
            </div> 
            <div class="form-group">
                <label for="address">{{ __('cruds.user.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address',$deliverMan->user->address) }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.user.fields.address_helper') }}</span>
            </div> 
            <div class="form-group">
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
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ __('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection