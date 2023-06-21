@extends('layouts.admin')
@section('content')

    <div class="form-group">
        <a class="btn btn-dark" href="{{ route('admin.receipt-socials.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    @include('partials.delivery_man',[
                                        'general_settings' => $general_settings,
                                        'row' => $receiptSocial, 
                                        'crudRoutePart' => 'admin.receipt-socials.',
                                        'response' => $response,
                                    ])

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.receiptSocial.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.receipt-socials.update', [$receiptSocial->id]) }}"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="required"
                                        for="client_name">{{ trans('cruds.receiptSocial.fields.client_name') }}</label>
                                    <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}"
                                        type="text" name="client_name" id="client_name"
                                        value="{{ old('client_name', $receiptSocial->client_name) }}" required>
                                    @if ($errors->has('client_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('client_name') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.client_name_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="required"
                                        for="phone_number">{{ trans('cruds.receiptSocial.fields.phone_number') }}</label>
                                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                                        type="text" name="phone_number" id="phone_number"
                                        value="{{ old('phone_number', $receiptSocial->phone_number) }}" required>
                                    @if ($errors->has('phone_number'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone_number') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.phone_number_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="date_of_receiving_order">{{ trans('cruds.receiptSocial.fields.date_of_receiving_order') }}</label>
                                    <input
                                        class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}"
                                        type="text" name="date_of_receiving_order" id="date_of_receiving_order"
                                        value="{{ old('date_of_receiving_order', $receiptSocial->date_of_receiving_order) }}">
                                    @if ($errors->has('date_of_receiving_order'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date_of_receiving_order') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.date_of_receiving_order_helper') }}</span>
                                </div>

                                <div class="form-group">
                                    <label class="required"
                                        for="shipping_country_id">{{ trans('cruds.receiptSocial.fields.shipping_country') }}</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}"
                                        name="shipping_country_id" id="shipping_country_id" required>
                                        @foreach ($shipping_countries as $id => $entry)
                                            <option value="{{ $id }}"
                                                {{ (old('shipping_country_id') ? old('shipping_country_id') : $receiptSocial->shipping_country->id ?? '') == $id ? 'selected' : '' }}>
                                                {{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('shipping_country'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('shipping_country') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_country_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required">{{ trans('cruds.receiptSocial.fields.client_type') }}</label>
                                    <select class="form-control {{ $errors->has('client_type') ? 'is-invalid' : '' }}"
                                        name="client_type" id="client_type" required>
                                        <option value disabled {{ old('client_type', null) === null ? 'selected' : '' }}>
                                            {{ trans('global.pleaseSelect') }}</option>
                                        @foreach (App\Models\ReceiptSocial::CLIENT_TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('client_type', $receiptSocial->client_type) === (string) $key ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('client_type'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('client_type') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.client_type_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="phone_number_2">{{ trans('cruds.receiptSocial.fields.phone_number_2') }}</label>
                                    <input class="form-control {{ $errors->has('phone_number_2') ? 'is-invalid' : '' }}"
                                        type="text" name="phone_number_2" id="phone_number_2"
                                        value="{{ old('phone_number_2', $receiptSocial->phone_number_2) }}">
                                    @if ($errors->has('phone_number_2'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone_number_2') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.phone_number_2_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label
                                        for="deliver_date">{{ trans('cruds.receiptSocial.fields.deliver_date') }}</label>
                                    <input
                                        class="form-control date {{ $errors->has('deliver_date') ? 'is-invalid' : '' }}"
                                        type="text" name="deliver_date" id="deliver_date"
                                        value="{{ old('deliver_date', $receiptSocial->deliver_date) }}">
                                    @if ($errors->has('deliver_date'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deliver_date') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.deliver_date_helper') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="deposit">{{ trans('cruds.receiptSocial.fields.deposit') }}</label>
                                    <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}"
                                        type="number" name="deposit" id="deposit"
                                        value="{{ old('deposit', $receiptSocial->deposit) }}" step="0.01">
                                    @if ($errors->has('deposit'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('deposit') }}
                                        </div>
                                    @endif
                                    <span
                                        class="help-block">{{ trans('cruds.receiptSocial.fields.deposit_helper') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="socials">{{ trans('cruds.receiptSocial.fields.socials') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                    style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                    style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('socials') ? 'is-invalid' : '' }}"
                                name="socials[]" id="socials" multiple>
                                @foreach ($socials as $id => $social)
                                    <option value="{{ $id }}"
                                        {{ in_array($id, old('socials', [])) || $receiptSocial->socials->contains($id) ? 'selected' : '' }}>
                                        {{ $social }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('socials'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('socials') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.receiptSocial.fields.socials_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <label class="required"
                                for="shipping_address">{{ trans('cruds.receiptSocial.fields.shipping_address') }}</label>
                            <textarea class="form-control {{ $errors->has('shipping_address') ? 'is-invalid' : '' }}" name="shipping_address"
                                id="shipping_address" required rows="6">{{ old('shipping_address', $receiptSocial->shipping_address) }}</textarea>
                            @if ($errors->has('shipping_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('shipping_address') }}
                                </div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.receiptSocial.fields.shipping_address_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="note">{{ trans('cruds.receiptSocial.fields.note') }}</label>
                            <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note"
                                rows="6">{{ old('note', $receiptSocial->note) }}</textarea>
                            @if ($errors->has('note'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('note') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.receiptSocial.fields.note_helper') }}</span>
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
