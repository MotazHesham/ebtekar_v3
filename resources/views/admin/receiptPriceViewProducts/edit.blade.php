@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.receiptPriceViewProduct.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-price-view-products.update", [$receiptPriceViewProduct->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.receiptPriceViewProduct.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $receiptPriceViewProduct->description) }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptPriceViewProduct.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="price">{{ trans('cruds.receiptPriceViewProduct.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $receiptPriceViewProduct->price) }}" step="0.01" required>
                @if($errors->has('price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptPriceViewProduct.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="quantity">{{ trans('cruds.receiptPriceViewProduct.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', $receiptPriceViewProduct->quantity) }}" step="1" required>
                @if($errors->has('quantity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('quantity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptPriceViewProduct.fields.quantity_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_cost">{{ trans('cruds.receiptPriceViewProduct.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', $receiptPriceViewProduct->total_cost) }}" step="0.01" required>
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptPriceViewProduct.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="receipt_price_view_id">{{ trans('cruds.receiptPriceViewProduct.fields.receipt_price_view') }}</label>
                <select class="form-control select2 {{ $errors->has('receipt_price_view') ? 'is-invalid' : '' }}" name="receipt_price_view_id" id="receipt_price_view_id" required>
                    @foreach($receipt_price_views as $id => $entry)
                        <option value="{{ $id }}" {{ (old('receipt_price_view_id') ? old('receipt_price_view_id') : $receiptPriceViewProduct->receipt_price_view->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('receipt_price_view'))
                    <div class="invalid-feedback">
                        {{ $errors->first('receipt_price_view') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptPriceViewProduct.fields.receipt_price_view_helper') }}</span>
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