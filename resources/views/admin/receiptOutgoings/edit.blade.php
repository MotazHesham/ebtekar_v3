@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.receiptOutgoing.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-outgoings.update", [$receiptOutgoing->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="date_of_receiving_order">{{ trans('cruds.receiptOutgoing.fields.date_of_receiving_order') }}</label>
                <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order', $receiptOutgoing->date_of_receiving_order) }}">
                @if($errors->has('date_of_receiving_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_of_receiving_order') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.date_of_receiving_order_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="client_name">{{ trans('cruds.receiptOutgoing.fields.client_name') }}</label>
                <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $receiptOutgoing->client_name) }}" required>
                @if($errors->has('client_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('client_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.client_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.receiptOutgoing.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $receiptOutgoing->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_cost">{{ trans('cruds.receiptOutgoing.fields.total_cost') }}</label>
                <input class="form-control {{ $errors->has('total_cost') ? 'is-invalid' : '' }}" type="number" name="total_cost" id="total_cost" value="{{ old('total_cost', $receiptOutgoing->total_cost) }}" step="0.01" required>
                @if($errors->has('total_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.total_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.receiptOutgoing.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $receiptOutgoing->note) }}</textarea>
                @if($errors->has('note'))
                    <div class="invalid-feedback">
                        {{ $errors->first('note') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="staff_id">{{ trans('cruds.receiptOutgoing.fields.staff') }}</label>
                <select class="form-control select2 {{ $errors->has('staff') ? 'is-invalid' : '' }}" name="staff_id" id="staff_id" required>
                    @foreach($staff as $id => $entry)
                        <option value="{{ $id }}" {{ (old('staff_id') ? old('staff_id') : $receiptOutgoing->staff->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('staff'))
                    <div class="invalid-feedback">
                        {{ $errors->first('staff') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.receiptOutgoing.fields.staff_helper') }}</span>
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