@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.receiptClientProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-client-products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="website_setting_id">{{ trans('global.extra.website_setting_id') }}</label>
                <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}" name="website_setting_id" id="website_setting_id" required>
                    @foreach($websites as $id => $entry)
                        <option value="{{ $id }}" {{ old('website_setting_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('website_setting_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website_setting_id') }}
                    </div>
                @endif 
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.receiptClientProduct.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClientProduct.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.receiptClientProduct.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClientProduct.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price_parts">{{ trans('cruds.receiptClientProduct.fields.price_parts') }}</label>
                <input class="form-control {{ $errors->has('price_parts') ? 'is-invalid' : '' }}" type="number" name="price_parts" id="price_parts" value="{{ old('price_parts', '') }}" step="0.01" required>
                @if($errors->has('price_parts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price_parts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClientProduct.fields.price_parts_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price_permissions">{{ trans('cruds.receiptClientProduct.fields.price_permissions') }}</label>
                <input class="form-control {{ $errors->has('price_permissions') ? 'is-invalid' : '' }}" type="number" name="price_permissions" id="price_permissions" value="{{ old('price_permissions', '') }}" step="0.01" required>
                @if($errors->has('price_permissions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price_permissions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClientProduct.fields.price_permissions_helper') }}</span>
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