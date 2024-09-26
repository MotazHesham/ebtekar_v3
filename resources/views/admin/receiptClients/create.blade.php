@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-dark" href="{{ route('admin.receipt-clients.index') }}">
        {{ __('global.back_to_list') }}
    </a>
</div>
<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.receiptClient.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-clients.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="required" for="website_setting_id">{{ __('global.extra.website_setting_id') }}</label>
                    <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}" name="website_setting_id" id="website_setting_id" required>
                        @foreach($websites as $id => $entry)
                            <option value="{{ $id }}" {{ old('website_setting_id',$website_setting_id) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('website_setting_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('website_setting_id') }}
                        </div>
                    @endif 
                </div>
                <div class="form-group col-md-4">
                    <label for="date_of_receiving_order">{{ __('cruds.receiptClient.fields.date_of_receiving_order') }}</label>
                    <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order') }}">
                    @if($errors->has('date_of_receiving_order'))
                        <div class="invalid-feedback">
                            {{ $errors->first('date_of_receiving_order') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.date_of_receiving_order_helper') }}</span>
                </div> 
                <div class="form-group col-md-4">
                    <label class="required" for="client_name">{{ __('cruds.receiptClient.fields.client_name') }}</label>
                    <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $previous_data['client_name']) }}" required>
                    @if($errors->has('client_name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('client_name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.client_name_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="phone_number">{{ __('cruds.receiptClient.fields.phone_number') }}</label>
                    <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $previous_data['phone_number']) }}" required>
                    @if($errors->has('phone_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('phone_number') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.phone_number_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label class="required" for="deposit">{{ __('cruds.receiptClient.fields.deposit') }}</label>
                    <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" type="number" name="deposit" id="deposit" value="{{ old('deposit') }}" step="0.01" onkeyup="change_deposit()" required>
                    @if($errors->has('deposit'))
                        <div class="invalid-feedback">
                            {{ $errors->first('deposit') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.deposit_helper') }}</span>
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

                <div class="form-group" id="financial_account_div" style="display: none">
                    <label class="required"
                        for="financial_account_id">{{ __('cruds.receiptSocial.fields.financial_account_id') }}</label>
                    <select
                        class="form-control select2 {{ $errors->has('shipping_country') ? 'is-invalid' : '' }}"
                        name="financial_account_id" id="financial_account_id" required>
                        <option  value="">{{ __('global.pleaseSelect') }}</option>
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
                <div class="form-group col-md-4">
                    <label for="discount">{{ __('cruds.receiptClient.fields.discount') }}</label>
                    <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', '') }}" step="0.01">
                    @if($errors->has('discount'))
                        <div class="invalid-feedback">
                            {{ $errors->first('discount') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.discount_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="note">{{ __('cruds.receiptClient.fields.note') }}</label>
                    <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note" rows="4">{{ old('note') }}</textarea>
                    @if($errors->has('note'))
                        <div class="invalid-feedback">
                            {{ $errors->first('note') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptClient.fields.note_helper') }}</span>
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
        function change_deposit(){
            var deposit = document.getElementById("deposit").value;
            if(deposit > 0){
                console.log(deposit);
                $('#deposit_type').prop('required', true);
                $('#financial_account_id').prop('required', true);
                $('#deposit_type_div').css('display','block');
                $('#financial_account_div').css('display','block');
            }else{
                $('#deposit_type').prop('required', false);
                $('#financial_account_id').prop('required', false);
                $('#deposit_type_div').css('display','none');
                $('#financial_account_div').css('display','none');
            }
        }
    </script>
@endsection