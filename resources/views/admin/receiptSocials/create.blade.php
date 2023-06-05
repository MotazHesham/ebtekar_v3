@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.receiptSocial.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-socials.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="client_name">{{ trans('cruds.receiptSocial.fields.client_name') }}</label>
                <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', '') }}" required>
                @if($errors->has('client_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.client_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptSocial.fields.client_type') }}</label>
                <select class="form-control {{ $errors->has('client_type') ? 'is-invalid' : '' }}" name="client_type" id="client_type" required>
                    <option value disabled {{ old('client_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptSocial::CLIENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('client_type', 'individual') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('client_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.client_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.receiptSocial.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone_number_2">{{ trans('cruds.receiptSocial.fields.phone_number_2') }}</label>
                <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}" type="text" name="phone_number_2" id="phone_number_2" value="{{ old('phone_number_2', '') }}">
                @if($errors->has('phone_number_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.phone_number_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deposit">{{ trans('cruds.receiptSocial.fields.deposit') }}</label>
                <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" type="number" name="deposit" id="deposit" value="{{ old('deposit', '0') }}" step="0.01">
                @if($errors->has('deposit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.deposit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.receiptSocial.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '0') }}" step="0.01">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="commission">{{ trans('cruds.receiptSocial.fields.commission') }}</label>
                <input class="form-control {{ $errors->has('commission') ? 'is-invalid' : '' }}" type="number" name="commission" id="commission" value="{{ old('commission', '0') }}" step="0.01">
                @if($errors->has('commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('commission') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra_commission">{{ trans('cruds.receiptSocial.fields.extra_commission') }}</label>
                <input class="form-control {{ $errors->has('extra_commission') ? 'is-invalid' : '' }}" type="number" name="extra_commission" id="extra_commission" value="{{ old('extra_commission', '0') }}" step="0.01">
                @if($errors->has('extra_commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('extra_commission') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.extra_commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total_cost">{{ trans('cruds.receiptSocial.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', '0') }}" step="0.01">
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_name">{{ trans('cruds.receiptSocial.fields.shipping_country_name') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_name') ? 'is-invalid' : '' }}" type="text" name="shipping_country_name" id="shipping_country_name" value="{{ old('shipping_country_name', '') }}" required>
                @if($errors->has('shipping_country_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_country_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_cost">{{ trans('cruds.receiptSocial.fields.shipping_country_cost') }}</label>
                <input class="form-control {{ $errors->has('shipping_country_cost') ? 'is-invalid' : '' }}" type="number" name="shipping_country_cost" id="shipping_country_cost" value="{{ old('shipping_country_cost', '') }}" step="0.01" required>
                @if($errors->has('shipping_country_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_country_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_country_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_address">{{ trans('cruds.receiptSocial.fields.shipping_address') }}</label>
                <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address" id="shipping_address" required>{{ old('shipping_address') }}</textarea>
                @if($errors->has('shipping_address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shipping_address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_of_receiving_order">{{ trans('cruds.receiptSocial.fields.date_of_receiving_order') }}</label>
                <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order') }}">
                @if($errors->has('date_of_receiving_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_of_receiving_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.date_of_receiving_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deliver_date">{{ trans('cruds.receiptSocial.fields.deliver_date') }}</label>
                <input class="form-control date {{ $errors->has('deliver_date') ? 'is-invalid' : '' }}" type="text" name="deliver_date" id="deliver_date" value="{{ old('deliver_date') }}">
                @if($errors->has('deliver_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deliver_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.deliver_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="delay_reason">{{ trans('cruds.receiptSocial.fields.delay_reason') }}</label>
                <textarea class="form-control {{ $errors->has('delay_reason') ? 'is-invalid' : '' }}" name="delay_reason" id="delay_reason">{{ old('delay_reason') }}</textarea>
                @if($errors->has('delay_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delay_reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.delay_reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptSocial.fields.delivery_status') }}</label>
                <select class="form-control {{ $errors->has('delivery_status') ? 'is-invalid' : '' }}" name="delivery_status" id="delivery_status" required>
                    <option value disabled {{ old('delivery_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptSocial::DELIVERY_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('delivery_status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('delivery_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.delivery_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.receiptSocial.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptSocial.fields.payment_status') }}</label>
                <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status" id="payment_status" required>
                    <option value disabled {{ old('payment_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptSocial::PAYMENT_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_status', 'unpaid') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.payment_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.receiptSocial.fields.playlist_status') }}</label>
                <select class="form-control {{ $errors->has('playlist_status') ? 'is-invalid' : '' }}" name="playlist_status" id="playlist_status" required>
                    <option value disabled {{ old('playlist_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\ReceiptSocial::PLAYLIST_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('playlist_status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('playlist_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('playlist_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.playlist_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="staff_id">{{ trans('cruds.receiptSocial.fields.staff') }}</label>
                <select class="form-control select2 {{ $errors->has('staff') ? 'is-invalid' : '' }}" name="staff_id" id="staff_id" required>
                    @foreach($staff as $id => $entry)
                        <option value="{{ $id }}" {{ old('staff_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('staff'))
                    <div class="invalid-feedback">
                        {{ $errors->first('staff') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.staff_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="shipping_country_id">{{ trans('cruds.receiptSocial.fields.shipping_country') }}</label>
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
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="socials">{{ trans('cruds.receiptSocial.fields.socials') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('socials') ? 'is-invalid' : '' }}" name="socials[]" id="socials" multiple>
                    @foreach($socials as $id => $social)
                        <option value="{{ $id }}" {{ in_array($id, old('socials', [])) ? 'selected' : '' }}>{{ $social }}</option>
                    @endforeach
                </select>
                @if($errors->has('socials'))
                    <div class="invalid-feedback">
                        {{ $errors->first('socials') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptSocial.fields.socials_helper') }}</span>
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