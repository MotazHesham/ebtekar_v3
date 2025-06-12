@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-dark" href="{{ route('admin.receipt-socials.index') }}">
            {{ __('global.back_to_list') }}
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            {{ __('global.create') }} {{ __('cruds.receiptSocial.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.receipt-socials.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required"
                                        for="website_setting_id">{{ __('global.extra.website_setting_id') }}</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}"
                                        name="website_setting_id" id="website_setting_id" required>
                                        @foreach ($websites as $id => $entry)
                                            <option value="{{ $id }}"
                                                {{ old('website_setting_id', $website_setting_id) == $id ? 'selected' : '' }}>
                                                {{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('website_setting_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('website_setting_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required"
                                        for="client_name">{{ __('cruds.receiptSocial.fields.client_name') }}</label>
                                    <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}"
                                        type="text" name="client_name" id="client_name"
                                        value="{{ old('client_name', $previous_data['client_name']) }}" required>
                                    @if ($errors->has('client_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('client_name') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.client_name_helper') }}</span>
                                </div>


                                <div class="form-group">
                                    <label class="required"
                                        for="phone_number">{{ __('cruds.receiptSocial.fields.phone_number') }}</label>
                                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                                        type="text" name="phone_number" id="phone_number"
                                        value="{{ old('phone_number', $previous_data['phone_number'] ?? request('phone_number')) }}"
                                        required>
                                    @if ($errors->has('phone_number'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone_number') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.phone_number_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label
                                        for="date_of_receiving_order">{{ __('cruds.receiptSocial.fields.date_of_receiving_order') }}</label>
                                    <input
                                        class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}"
                                        type="text" name="date_of_receiving_order" id="date_of_receiving_order"
                                        value="{{ old('date_of_receiving_order') }}">
                                    @if ($errors->has('date_of_receiving_order'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date_of_receiving_order') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.date_of_receiving_order_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label 
                                        for="discount_type">{{ __('cruds.receiptSocial.fields.discount_type') }}</label>
                                    <select class="form-control {{ $errors->has('discount_type') ? 'is-invalid' : '' }}"
                                        name="discount_type" id="discount_type"  >
                                        <option value disabled {{ old('discount_type', null) === null ? 'selected' : '' }}>
                                            {{ __('global.pleaseSelect') }}</option>
                                        @foreach (App\Models\ReceiptSocial::DISCOUNT_TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('discount_type') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('discount_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('discount_type') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.discount_type_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label class="required"
                                        for="shipping_country_id">{{ __('cruds.receiptSocial.fields.shipping_country_id') }}</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}"
                                        name="shipping_country_id" id="shipping_country_id" required>
                                        <option value="">{{ __('global.pleaseSelect') }}</option>
                                        @foreach ($shipping_countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('shipping_country_id', $previous_data['shipping_country_id']) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }} - {{ $country->cost }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('shipping_country'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_country') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.shipping_country_id_helper') }}</span>
                                </div>
                                <div class="form-group" id="deposit_type_div" style="display: none">
                                    <label class="required">{{ __('cruds.receiptSocial.fields.deposit_type') }}</label>
                                    <select class="form-control {{ $errors->has('deposit_type') ? 'is-invalid' : '' }}"
                                        name="deposit_type" id="deposit_type" required>
                                        <option value disabled {{ old('deposit_type', null) === null ? 'selected' : '' }}>
                                            {{ __('global.pleaseSelect') }}</option>
                                        @foreach (App\Models\ReceiptSocial::DEPOSIT_TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('deposit_type') === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('deposit_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deposit_type') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.deposit_type_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="required">{{ __('cruds.receiptSocial.fields.client_type') }}</label>
                                    <select class="form-control {{ $errors->has('client_type') ? 'is-invalid' : '' }}"
                                        name="client_type" id="client_type" required>
                                        <option value disabled {{ old('client_type', null) === null ? 'selected' : '' }}>
                                            {{ __('global.pleaseSelect') }}</option>
                                        @foreach (App\Models\ReceiptSocial::CLIENT_TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('client_type', $previous_data['client_type']) === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('client_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('client_type') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.client_type_helper') }}</span>
                                </div>


                                <div class="form-group">
                                    <label
                                        for="phone_number_2">{{ __('cruds.receiptSocial.fields.phone_number_2') }}</label>
                                    <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}"
                                        type="text" name="phone_number_2" id="phone_number_2"
                                        value="{{ old('phone_number_2', $previous_data['phone_number_2']) }}">
                                    @if ($errors->has('phone_number_2'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone_number_2') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.phone_number_2_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="deliver_date">{{ __('cruds.receiptSocial.fields.deliver_date') }}</label>
                                    <input
                                        class="form-control date {{ $errors->has('deliver_date') ? 'is-invalid' : '' }}"
                                        type="text" name="deliver_date" id="deliver_date"
                                        value="{{ old('deliver_date') }}">
                                    @if ($errors->has('deliver_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deliver_date') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.deliver_date_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label 
                                        for="discount">{{ __('cruds.receiptSocial.fields.discount') }}</label>
                                    <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}"
                                        type="number" name="discount" id="discount" value="{{ old('discount') }}"
                                        step="0.01"  >
                                    @if ($errors->has('discount'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('discount') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ __('cruds.receiptSocial.fields.discount_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="deposit"
                                        class="required">{{ __('cruds.receiptSocial.fields.deposit') }}</label>
                                    <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}"
                                        type="number" name="deposit" id="deposit" value="{{ old('deposit') }}"
                                        step="0.01" required onkeyup="change_deposit()">
                                    @if ($errors->has('deposit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deposit') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ __('cruds.receiptSocial.fields.deposit_helper') }}</span>
                                </div>
                                <div class="form-group" id="financial_account_div" style="display: none">
                                    <label class="required"
                                        for="financial_account_id">{{ __('cruds.receiptSocial.fields.financial_account_id') }}</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}"
                                        name="financial_account_id" id="financial_account_id" required>
                                        <option value="">{{ __('global.pleaseSelect') }}</option>
                                        @foreach ($financial_accounts as $raw)
                                            <option value="{{ $raw->id }}"
                                                {{ old('financial_account_id') == $raw->id ? 'selected' : '' }}>
                                                {{ $raw->account }} - {{ $raw->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('financial_account_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('financial_account_id') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ __('cruds.receiptSocial.fields.financial_account_id_helper') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="socials">{{ __('cruds.receiptSocial.fields.socials') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                    style="border-radius: 0">{{ __('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                    style="border-radius: 0">{{ __('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('socials') ? 'is-invalid' : '' }}"
                                name="socials[]" id="socials" multiple>
                                @foreach ($socials as $id => $social)
                                    <option value="{{ $id }}"
                                        {{ in_array($id, old('socials', [])) ? 'selected' : '' }}>{{ $social }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('socials'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('socials') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.receiptSocial.fields.socials_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="required"
                                for="shipping_address">{{ __('cruds.receiptSocial.fields.shipping_address') }}</label>
                            <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address"
                                id="shipping_address" required rows="6">{{ old('shipping_address', $previous_data['shipping_address']) }}</textarea>
                            @if ($errors->has('shipping_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('shipping_address') }}
                                </div>
                            @endif
                            <span
                                class="help-block">{{ __('cruds.receiptSocial.fields.shipping_address_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="note">{{ __('cruds.receiptSocial.fields.note') }}</label>
                            <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note"
                                rows="6">{{ old('note') }}</textarea>
                            @if ($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                            <span class="help-block">{{ __('cruds.receiptSocial.fields.note_helper') }}</span>
                        </div>
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

@section('scripts')
    @parent
    <script>
        function change_deposit() {
            var deposit = document.getElementById("deposit").value;
            if (deposit > 0) {
                console.log(deposit);
                $('#deposit_type').prop('required', true);
                $('#financial_account_id').prop('required', true);
                $('#deposit_type_div').css('display', 'block');
                $('#financial_account_div').css('display', 'block');
            } else {
                $('#deposit_type').prop('required', false);
                $('#financial_account_id').prop('required', false);
                $('#deposit_type_div').css('display', 'none');
                $('#financial_account_div').css('display', 'none');
            }
        }
    </script>
@endsection
