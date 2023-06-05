@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.receiptOutgoingProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-outgoing-products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="description">{{ trans('cruds.receiptOutgoingProduct.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}">
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoingProduct.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.receiptOutgoingProduct.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoingProduct.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="quantity">{{ trans('cruds.receiptOutgoingProduct.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', '1') }}" step="1" required>
                @if($errors->has('quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoingProduct.fields.quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_cost">{{ trans('cruds.receiptOutgoingProduct.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', '') }}" step="0.01" required>
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoingProduct.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="receipt_outgoing_id">{{ trans('cruds.receiptOutgoingProduct.fields.receipt_outgoing') }}</label>
                <select class="form-control select2 {{ $errors->has('receipt_outgoing') ? 'is-invalid' : '' }}" name="receipt_outgoing_id" id="receipt_outgoing_id">
                    @foreach($receipt_outgoings as $id => $entry)
                        <option value="{{ $id }}" {{ old('receipt_outgoing_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('receipt_outgoing'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receipt_outgoing') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoingProduct.fields.receipt_outgoing_helper') }}</span>
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