@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.country.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.update", [$country->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.country.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $country->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shopify_name">{{ __('cruds.country.fields.shopify_name') }}</label>
                <input class="form-control {{ $errors->has('shopify_name') ? 'is-invalid' : '' }}" type="text" name="shopify_name" id="shopify_name" value="{{ old('shopify_name', $country->shopify_name) }}"  >
                @if($errors->has('shopify_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shopify_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.shopify_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="cost">{{ __('cruds.country.fields.cost') }}</label>
                <input class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }}" type="number" name="cost" id="cost" value="{{ old('cost', $country->cost) }}" step="0.01" required>
                @if($errors->has('cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cost') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="code">{{ __('cruds.country.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $country->code) }}">
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="code_cost">{{ __('cruds.country.fields.code_cost') }}</label>
                <input class="form-control {{ $errors->has('code_cost') ? 'is-invalid' : '' }}" type="number" name="code_cost" id="code_cost" value="{{ old('code_cost', $country->code_cost) }}" step="0.01">
                @if($errors->has('code_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.code_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.country.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Country::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $country->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.country.fields.type_helper') }}</span>
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