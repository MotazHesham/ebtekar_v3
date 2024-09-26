@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.employee.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.employees.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.employee.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ __('cruds.employee.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ __('cruds.employee.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="salery">{{ __('cruds.employee.fields.salery') }}</label>
                <input class="form-control {{ $errors->has('salery') ? 'is-invalid' : '' }}" type="number" name="salery" id="salery" value="{{ old('salery', '') }}" step="0.01" required>
                @if($errors->has('salery'))
                    <div class="invalid-feedback">
                        {{ $errors->first('salery') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.salery_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ __('cruds.employee.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', '') }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="job_description">{{ __('cruds.employee.fields.job_description') }}</label>
                <input class="form-control {{ $errors->has('job_description') ? 'is-invalid' : '' }}" type="text" name="job_description" id="job_description" value="{{ old('job_description', '') }}">
                @if($errors->has('job_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('job_description') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.employee.fields.job_description_helper') }}</span>
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