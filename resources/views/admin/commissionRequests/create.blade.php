@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.commissionRequest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.commission-requests.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ __('cruds.commissionRequest.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CommissionRequest::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="total_commission">{{ __('cruds.commissionRequest.fields.total_commission') }}</label>
                <input class="form-control {{ $errors->has('total_commission') ? 'is-invalid' : '' }}" type="number" name="total_commission" id="total_commission" value="{{ old('total_commission', '') }}" step="0.01" required>
                @if($errors->has('total_commission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total_commission') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.total_commission_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.commissionRequest.fields.payment_method') }}</label>
                <select class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" name="payment_method" id="payment_method" required>
                    <option value disabled {{ old('payment_method', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CommissionRequest::PAYMENT_METHOD_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_method', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_method'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_method') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.payment_method_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="transfer_number">{{ __('cruds.commissionRequest.fields.transfer_number') }}</label>
                <input class="form-control {{ $errors->has('transfer_number') ? 'is-invalid' : '' }}" type="text" name="transfer_number" id="transfer_number" value="{{ old('transfer_number', '') }}">
                @if($errors->has('transfer_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transfer_number') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.transfer_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ __('cruds.commissionRequest.fields.user') }}</label>
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
                <span class="help-block">{{ __('cruds.commissionRequest.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="created_by_id">{{ __('cruds.commissionRequest.fields.created_by') }}</label>
                <select class="form-control select2 {{ $errors->has('created_by') ? 'is-invalid' : '' }}" name="created_by_id" id="created_by_id" required>
                    @foreach($created_bies as $id => $entry)
                        <option value="{{ $id }}" {{ old('created_by_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('created_by'))
                    <div class="invalid-feedback">
                        {{ $errors->first('created_by') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.created_by_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="done_by_user_id">{{ __('cruds.commissionRequest.fields.done_by_user') }}</label>
                <select class="form-control select2 {{ $errors->has('done_by_user') ? 'is-invalid' : '' }}" name="done_by_user_id" id="done_by_user_id">
                    @foreach($done_by_users as $id => $entry)
                        <option value="{{ $id }}" {{ old('done_by_user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('done_by_user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('done_by_user') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.commissionRequest.fields.done_by_user_helper') }}</span>
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