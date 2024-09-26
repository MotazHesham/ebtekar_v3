@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.order.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.orders.update", [$order->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf 
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="required" for="client_name">{{ __('cruds.order.fields.client_name') }}</label>
                    <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $order->client_name) }}" required>
                    @if($errors->has('client_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('client_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.client_name_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="phone_number">{{ __('cruds.order.fields.phone_number') }}</label>
                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $order->phone_number) }}" required>
                    @if($errors->has('phone_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.phone_number_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="phone_number_2">{{ __('cruds.order.fields.phone_number_2') }}</label>
                    <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}" type="text" name="phone_number_2" id="phone_number_2" value="{{ old('phone_number_2', $order->phone_number_2) }}" required>
                    @if($errors->has('phone_number_2'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number_2') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.phone_number_2_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="shipping_address">{{ __('cruds.order.fields.shipping_address') }}</label>
                    <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address" id="shipping_address" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                    @if($errors->has('shipping_address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('shipping_address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.shipping_address_helper') }}</span>
                </div> 
                <div class="form-group col-md-3">
                    <label class="required" for="shipping_country_id">{{ __('cruds.order.fields.shipping_country_name') }}</label>
                    <select class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}" name="shipping_country_id" id="shipping_country_id" required> 
                        <option  value="">{{ __('global.pleaseSelect') }}</option>
                        @foreach ($shipping_countries as  $country)
                            <option value="{{ $country->id }}"
                                {{ (old('shipping_country_id') ? old('shipping_country_id') : $order->shipping_country->id ?? '') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }} - {{ $country->cost }}
                            </option>
                        @endforeach
                    </select> 
                    <span class="help-block">{{ __('cruds.order.fields.shipping_country_name_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="shipping_country_cost">{{ __('cruds.order.fields.shipping_country_cost') }}</label>
                    <input class="form-control {{ $errors->has('shipping_country_cost') ? 'is-invalid' : '' }}" type="number" name="shipping_country_cost" id="shipping_country_cost" value="{{ old('shipping_country_cost', $order->shipping_country_cost) }}" step="0.01" required>
                    @if($errors->has('shipping_country_cost'))
                        <div class="invalid-feedback">
                            {{ $errors->first('shipping_country_cost') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.shipping_country_cost_helper') }}</span>
                </div> 
                <div class="form-group col-md-3">
                    <label for="excepected_deliverd_date">{{ __('cruds.order.fields.excepected_deliverd_date') }}</label>
                    <input class="form-control date {{ $errors->has('excepected_deliverd_date') ? 'is-invalid' : '' }}" type="text" name="excepected_deliverd_date" id="excepected_deliverd_date" value="{{ old('excepected_deliverd_date', $order->excepected_deliverd_date) }}">
                    @if($errors->has('excepected_deliverd_date'))
                        <div class="invalid-feedback">
                            {{ $errors->first('excepected_deliverd_date') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.excepected_deliverd_date_helper') }}</span>
                </div> 
                <div class="form-group col-md-3">
                    <label>{{ __('cruds.order.fields.deposit_type') }}</label>
                    <select class="form-control {{ $errors->has('deposit_type') ? 'is-invalid' : '' }}" name="deposit_type" id="deposit_type">
                        <option value disabled {{ old('deposit_type', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                        @foreach(App\Models\Order::DEPOSIT_TYPE_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('deposit_type', $order->deposit_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('deposit_type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('deposit_type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.deposit_type_helper') }}</span>
                </div>
                <div class="form-group col-md-3">
                    <label for="deposit_amount">{{ __('cruds.order.fields.deposit_amount') }}</label>
                    <input class="form-control {{ $errors->has('deposit_amount') ? 'is-invalid' : '' }}" type="number" name="deposit_amount" id="deposit_amount" value="{{ old('deposit_amount', $order->deposit_amount) }}" step="0.01">
                    @if($errors->has('deposit_amount'))
                        <div class="invalid-feedback">
                            {{ $errors->first('deposit_amount') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.deposit_amount_helper') }}</span>
                </div> 
                <div class="form-group col-md-6">
                    <label for="note">{{ __('cruds.order.fields.note') }}</label>
                    <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note"
                        rows="6">{{ old('note',$order->note) }}</textarea>
                    @if ($errors->has('note'))
                        <div class="invalid-feedback">
                            {{ $errors->first('note') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.order.fields.note_helper') }}</span>
                </div>
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