@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.currency.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.currencies.update", [$currency->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row"> 
                <div class="form-group col-md-3">
                    <label class="required" for="name">{{ trans('cruds.currency.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $currency->name) }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.name_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="symbol">{{ trans('cruds.currency.fields.symbol') }}</label>
                    <input class="form-control {{ $errors->has('symbol') ? 'is-invalid' : '' }}" type="text" name="symbol" id="symbol" value="{{ old('symbol', $currency->symbol) }}" required>
                    @if($errors->has('symbol'))
                        <div class="invalid-feedback">
                            {{ $errors->first('symbol') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.symbol_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="exchange_rate">{{ trans('cruds.currency.fields.exchange_rate') }}</label>
                    <input class="form-control {{ $errors->has('exchange_rate') ? 'is-invalid' : '' }}" type="number" name="exchange_rate" id="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate) }}" step="0.01" required>
                    @if($errors->has('exchange_rate'))
                        <div class="invalid-feedback">
                            {{ $errors->first('exchange_rate') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.exchange_rate_helper') }}</span>
                </div> 
                <div class="form-group col-md-3">
                    <label class="required" for="code">{{ trans('cruds.currency.fields.code') }}</label>
                    <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $currency->code) }}" required>
                    @if($errors->has('code'))
                        <div class="invalid-feedback">
                            {{ $errors->first('code') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.code_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="half_kg">{{ trans('cruds.currency.fields.half_kg') }}</label>
                    <input class="form-control {{ $errors->has('half_kg') ? 'is-invalid' : '' }}" type="number" name="half_kg" id="half_kg" value="{{ old('half_kg', $currency->half_kg) }}" step="0.01" required>
                    @if($errors->has('half_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('half_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.half_kg_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="one_kg">{{ trans('cruds.currency.fields.one_kg') }}</label>
                    <input class="form-control {{ $errors->has('one_kg') ? 'is-invalid' : '' }}" type="number" name="one_kg" id="one_kg" value="{{ old('one_kg', $currency->one_kg) }}" step="0.01" required>
                    @if($errors->has('one_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('one_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.one_kg_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="one_half_kg">{{ trans('cruds.currency.fields.one_half_kg') }}</label>
                    <input class="form-control {{ $errors->has('one_half_kg') ? 'is-invalid' : '' }}" type="number" name="one_half_kg" id="one_half_kg" value="{{ old('one_half_kg', $currency->one_half_kg) }}" step="0.01" required>
                    @if($errors->has('one_half_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('one_half_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.one_half_kg_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="two_kg">{{ trans('cruds.currency.fields.two_kg') }}</label>
                    <input class="form-control {{ $errors->has('two_kg') ? 'is-invalid' : '' }}" type="number" name="two_kg" id="two_kg" value="{{ old('two_kg', $currency->two_kg) }}" step="0.01" required>
                    @if($errors->has('two_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('two_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.two_kg_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="two_half_kg">{{ trans('cruds.currency.fields.two_half_kg') }}</label>
                    <input class="form-control {{ $errors->has('two_half_kg') ? 'is-invalid' : '' }}" type="number" name="two_half_kg" id="two_half_kg" value="{{ old('two_half_kg', $currency->two_half_kg) }}" step="0.01" required>
                    @if($errors->has('two_half_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('two_half_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.two_half_kg_helper') }}</span>
                </div>
                <div class="form-group col-md-2">
                    <label class="required" for="three_kg">{{ trans('cruds.currency.fields.three_kg') }}</label>
                    <input class="form-control {{ $errors->has('three_kg') ? 'is-invalid' : '' }}" type="number" name="three_kg" id="three_kg" value="{{ old('three_kg', $currency->three_kg) }}" step="0.01" required>
                    @if($errors->has('three_kg'))
                        <div class="invalid-feedback">
                            {{ $errors->first('three_kg') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.currency.fields.three_kg_helper') }}</span>
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



@endsection