@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ __('cruds.order.fields.order_type') }}</label>
                <select class="form-control {{ $errors->has('order_type') ? 'is-invalid' : '' }}" name="order_type" id="order_type" required>
                    <option value disabled {{ old('order_type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::ORDER_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('order_type', 'customer') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('order_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.order_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="client_name">{{ __('cruds.order.fields.client_name') }}</label>
                <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', '') }}" required>
                @if($errors->has('client_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.client_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ __('cruds.order.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number_2">{{ __('cruds.order.fields.phone_number_2') }}</label>
                <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}" type="text" name="phone_number_2" id="phone_number_2" value="{{ old('phone_number_2', '') }}" required>
                @if($errors->has('phone_number_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number_2') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.phone_number_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_address">{{ __('cruds.order.fields.shipping_address') }}</label>
                <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address" id="shipping_address" required>{{ old('shipping_address') }}</textarea>
                @if($errors->has('shipping_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_address') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.shipping_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_name">{{ __('cruds.order.fields.shipping_country_name') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_name') ? 'is-invalid' : '' }}" type="text" name="shipping_country_name" id="shipping_country_name" value="{{ old('shipping_country_name', '') }}" required>
                @if($errors->has('shipping_country_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.shipping_country_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_cost">{{ __('cruds.order.fields.shipping_country_cost') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_cost') ? 'is-invalid' : '' }}" type="number" name="shipping_country_cost" id="shipping_country_cost" value="{{ old('shipping_country_cost', '') }}" step="0.01" required>
                @if($errors->has('shipping_country_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.shipping_country_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="printing_times">{{ __('cruds.order.fields.printing_times') }}</label>
                <input class="form-control {{ $errors->has('printing_times') ? 'is-invalid' : '' }}" type="number" name="printing_times" id="printing_times" value="{{ old('printing_times', '0') }}" step="1">
                @if($errors->has('printing_times'))
                    <div class="invalid-feedback">
                        {{ $errors->first('printing_times') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.printing_times_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_of_receiving_order">{{ __('cruds.order.fields.date_of_receiving_order') }}</label>
                <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order') }}">
                @if($errors->has('date_of_receiving_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_of_receiving_order') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.date_of_receiving_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="excepected_deliverd_date">{{ __('cruds.order.fields.excepected_deliverd_date') }}</label>
                <input class="form-control date {{ $errors->has('excepected_deliverd_date') ? 'is-invalid' : '' }}" type="text" name="excepected_deliverd_date" id="excepected_deliverd_date" value="{{ old('excepected_deliverd_date') }}">
                @if($errors->has('excepected_deliverd_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('excepected_deliverd_date') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.excepected_deliverd_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.order.fields.payment_status') }}</label>
                <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status" id="payment_status" required>
                    <option value disabled {{ old('payment_status', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::PAYMENT_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_status', 'unpaid') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_status') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.payment_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.order.fields.delivery_status') }}</label>
                <select class="form-control {{ $errors->has('delivery_status') ? 'is-invalid' : '' }}" name="delivery_status" id="delivery_status" required>
                    <option value disabled {{ old('delivery_status', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::DELIVERY_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('delivery_status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('delivery_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_status') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.delivery_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.order.fields.payment_type') }}</label>
                <select class="form-control {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type" required>
                    <option value disabled {{ old('payment_type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::PAYMENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_type', 'cash_on_delivery') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.payment_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ __('cruds.order.fields.deposit_type') }}</label>
                <select class="form-control {{ $errors->has('deposit_type') ? 'is-invalid' : '' }}" name="deposit_type" id="deposit_type">
                    <option value disabled {{ old('deposit_type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Order::DEPOSIT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('deposit_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('deposit_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit_type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.deposit_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deposit_amount">{{ __('cruds.order.fields.deposit_amount') }}</label>
                <input class="form-control {{ $errors->has('deposit_amount') ? 'is-invalid' : '' }}" type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount', '') }}" step="0.01">
                @if($errors->has('deposit_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.deposit_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_cost_by_seller">{{ __('cruds.order.fields.total_cost_by_seller') }}</label>
                <input class="form-control {{ $errors->has('total_cost_by_seller') ? 'is-invalid' : '' }}" type="number" name="total_cost_by_seller" id="total_cost_by_seller" value="{{ old('total_cost_by_seller', '') }}" step="0.01">
                @if($errors->has('total_cost_by_seller'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost_by_seller') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.total_cost_by_seller_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="commission">{{ __('cruds.order.fields.commission') }}</label>
                <input class="form-control {{ $errors->has('commission') ? 'is-invalid' : '' }}" type="number" name="commission" id="commission" value="{{ old('commission', '') }}" step="0.01">
                @if($errors->has('commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('commission') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra_commission">{{ __('cruds.order.fields.extra_commission') }}</label>
                <input class="form-control {{ $errors->has('extra_commission') ? 'is-invalid' : '' }}" type="number" name="extra_commission" id="extra_commission" value="{{ old('extra_commission', '') }}" step="0.01">
                @if($errors->has('extra_commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('extra_commission') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.extra_commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ __('cruds.order.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" step="0.01">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount_code">{{ __('cruds.order.fields.discount_code') }}</label>
                <input class="form-control {{ $errors->has('discount_code') ? 'is-invalid' : '' }}" type="text" name="discount_code" id="discount_code" value="{{ old('discount_code', '') }}">
                @if($errors->has('discount_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount_code') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.discount_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ __('cruds.order.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_id">{{ __('cruds.order.fields.shipping_country') }}</label>
                <select class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}" name="shipping_country_id" id="shipping_country_id" required>
                    @foreach($shipping_countries as $id => $entry)
                        <option value="{{ $id }}" {{ old('shipping_country_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('shipping_country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.shipping_country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="preparer_id">{{ __('cruds.order.fields.preparer') }}</label>
                <select class="form-control select2 {{ $errors->has('preparer') ? 'is-invalid' : '' }}" name="preparer_id" id="preparer_id">
                    @foreach($preparers as $id => $entry)
                        <option value="{{ $id }}" {{ old('preparer_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('preparer'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preparer') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.preparer_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delivery_man_id">{{ __('cruds.order.fields.delivery_man') }}</label>
                <select class="form-control select2 {{ $errors->has('delivery_man') ? 'is-invalid' : '' }}" name="delivery_man_id" id="delivery_man_id">
                    @foreach($delivery_men as $id => $entry)
                        <option value="{{ $id }}" {{ old('delivery_man_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('delivery_man'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_man') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.order.fields.delivery_man_helper') }}</span>
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