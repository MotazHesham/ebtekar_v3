@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.receiptPriceView.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-price-views.update", [$receiptPriceView->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="date_of_receiving_order">{{ __('cruds.receiptPriceView.fields.date_of_receiving_order') }}</label>
                    <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order', $receiptPriceView->date_of_receiving_order) }}">
                    @if($errors->has('date_of_receiving_order'))
                        <div class="invalid-feedback">
                            {{ $errors->first('date_of_receiving_order') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.date_of_receiving_order_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="client_name">{{ __('cruds.receiptPriceView.fields.client_name') }}</label>
                    <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $receiptPriceView->client_name) }}" required>
                    @if($errors->has('client_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('client_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.client_name_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="phone_number">{{ __('cruds.receiptPriceView.fields.phone_number') }}</label>
                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $receiptPriceView->phone_number) }}" required>
                    @if($errors->has('phone_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.phone_number_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="place">{{ __('cruds.receiptPriceView.fields.place') }}</label>
                    <input class="form-control {{ $errors->has('place') ? 'is-invalid' : '' }}" type="text" name="place" id="place" value="{{ old('place', $receiptPriceView->place) }}">
                    @if($errors->has('place'))
                        <div class="invalid-feedback">
                            {{ $errors->first('place') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.place_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="relate_duration">{{ __('cruds.receiptPriceView.fields.relate_duration') }}</label>
                    <input class="form-control {{ $errors->has('relate_duration') ? 'is-invalid' : '' }}" type="text" name="relate_duration" id="relate_duration" value="{{ old('relate_duration', $receiptPriceView->relate_duration) }}">
                    @if($errors->has('relate_duration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('relate_duration') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.relate_duration_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="supply_duration">{{ __('cruds.receiptPriceView.fields.supply_duration') }}</label>
                    <input class="form-control {{ $errors->has('supply_duration') ? 'is-invalid' : '' }}" type="text" name="supply_duration" id="supply_duration" value="{{ old('supply_duration', $receiptPriceView->supply_duration) }}">
                    @if($errors->has('supply_duration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('supply_duration') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.supply_duration_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="payment">{{ __('cruds.receiptPriceView.fields.payment') }}</label>
                    <input class="form-control {{ $errors->has('payment') ? 'is-invalid' : '' }}" type="text" name="payment" id="payment" value="{{ old('payment', $receiptPriceView->payment) }}">
                    @if($errors->has('payment'))
                        <div class="invalid-feedback">
                            {{ $errors->first('payment') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.payment_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <div class="form-check {{ $errors->has('added_value') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="added_value" value="0">
                        <input class="form-check-input" type="checkbox" name="added_value" id="added_value" value="1" {{ $receiptPriceView->added_value || old('added_value', 0) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="added_value">{{ __('cruds.receiptPriceView.fields.added_value') }}</label>
                    </div>
                    @if($errors->has('added_value'))
                        <div class="invalid-feedback">
                            {{ $errors->first('added_value') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptPriceView.fields.added_value_helper') }}</span>
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