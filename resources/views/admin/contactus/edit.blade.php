@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.contactu.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.contactus.update", [$contactu->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="first_name">{{ __('cruds.contactu.fields.first_name') }}</label>
                <input class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name" id="first_name" value="{{ old('first_name', $contactu->first_name) }}">
                @if($errors->has('first_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('first_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.contactu.fields.first_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="last_name">{{ __('cruds.contactu.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $contactu->last_name) }}">
                @if($errors->has('last_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.contactu.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ __('cruds.contactu.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $contactu->email) }}">
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.contactu.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone_number">{{ __('cruds.contactu.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $contactu->phone_number) }}">
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.contactu.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message">{{ __('cruds.contactu.fields.message') }}</label>
                <textarea class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" name="message" id="message">{{ old('message', $contactu->message) }}</textarea>
                @if($errors->has('message'))
                    <div class="invalid-feedback">
                        {{ $errors->first('message') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.contactu.fields.message_helper') }}</span>
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