@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.receiptClient.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-clients.update", [$receiptClient->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="date_of_receiving_order">{{ trans('cruds.receiptClient.fields.date_of_receiving_order') }}</label>
                <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order', $receiptClient->date_of_receiving_order) }}">
                @if($errors->has('date_of_receiving_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_of_receiving_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.date_of_receiving_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="order_num">{{ trans('cruds.receiptClient.fields.order_num') }}</label>
                <input class="form-control {{ $errors->has('order_num') ? 'is-invalid' : '' }}" type="text" name="order_num" id="order_num" value="{{ old('order_num', $receiptClient->order_num) }}">
                @if($errors->has('order_num'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_num') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.order_num_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="client_name">{{ trans('cruds.receiptClient.fields.client_name') }}</label>
                <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $receiptClient->client_name) }}" required>
                @if($errors->has('client_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.client_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.receiptClient.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $receiptClient->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="deposit">{{ trans('cruds.receiptClient.fields.deposit') }}</label>
                <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" type="number" name="deposit" id="deposit" value="{{ old('deposit', $receiptClient->deposit) }}" step="0.01">
                @if($errors->has('deposit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deposit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.deposit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discount">{{ trans('cruds.receiptClient.fields.discount') }}</label>
                <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', $receiptClient->discount) }}" step="0.01">
                @if($errors->has('discount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.discount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.receiptClient.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $receiptClient->note) }}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="staff_id">{{ trans('cruds.receiptClient.fields.staff') }}</label>
                <select class="form-control select2 {{ $errors->has('staff') ? 'is-invalid' : '' }}" name="staff_id" id="staff_id" required>
                    @foreach($staff as $id => $entry)
                        <option value="{{ $id }}" {{ (old('staff_id') ? old('staff_id') : $receiptClient->staff->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('staff'))
                    <div class="invalid-feedback">
                        {{ $errors->first('staff') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptClient.fields.staff_helper') }}</span>
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