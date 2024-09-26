@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-dark" href="{{ route('admin.receipt-outgoings.index') }}">
        {{ __('global.back_to_list') }}
    </a>
</div>
<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.receiptOutgoing.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-outgoings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_of_receiving_order">{{ __('cruds.receiptOutgoing.fields.date_of_receiving_order') }}</label>
                        <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order') }}">
                        @if($errors->has('date_of_receiving_order'))
                            <div class="invalid-feedback">
                                {{ $errors->first('date_of_receiving_order') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.receiptOutgoing.fields.date_of_receiving_order_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="client_name">{{ __('cruds.receiptOutgoing.fields.client_name') }}</label>
                        <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', '') }}" required>
                        @if($errors->has('client_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('client_name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.receiptOutgoing.fields.client_name_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="phone_number">{{ __('cruds.receiptOutgoing.fields.phone_number') }}</label>
                        <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', '') }}" required>
                        @if($errors->has('phone_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ __('cruds.receiptOutgoing.fields.phone_number_helper') }}</span>
                    </div> 
                </div>
                <div class="form-group col-md-6">
                    <label for="note">{{ __('cruds.receiptOutgoing.fields.note') }}</label>
                    <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note" rows="8">{{ old('note') }}</textarea>
                    @if($errors->has('note'))
                        <div class="invalid-feedback">
                            {{ $errors->first('note') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptOutgoing.fields.note_helper') }}</span>
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