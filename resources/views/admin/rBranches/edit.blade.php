@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.rBranch.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.r-branches.update", [$rBranch->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.rBranch.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $rBranch->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.rBranch.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ __('cruds.rBranch.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $rBranch->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.rBranch.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ __('cruds.rBranch.fields.payment_type') }}</label>
                <select class="form-control {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type">
                    <option value disabled {{ old('payment_type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\RBranch::PAYMENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_type', $rBranch->payment_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.rBranch.fields.payment_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="r_client_id">{{ __('cruds.rBranch.fields.r_client') }}</label>
                <select class="form-control select2 {{ $errors->has('r_client') ? 'is-invalid' : '' }}" name="r_client_id" id="r_client_id" required>
                    @foreach($r_clients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('r_client_id') ? old('r_client_id') : $rBranch->r_client->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('r_client'))
                    <div class="invalid-feedback">
                        {{ $errors->first('r_client') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.rBranch.fields.r_client_helper') }}</span>
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