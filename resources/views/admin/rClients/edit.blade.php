@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.rClient.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.r-clients.update", [$rClient->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.rClient.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $rClient->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rClient.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.rClient.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $rClient->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rClient.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.rClient.fields.manage_type') }}</label>
                <select class="form-control {{ $errors->has('manage_type') ? 'is-invalid' : '' }}" name="manage_type" id="manage_type">
                    <option value disabled {{ old('manage_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\RClient::MANAGE_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('manage_type', $rClient->manage_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('manage_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('manage_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.rClient.fields.manage_type_helper') }}</span>
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