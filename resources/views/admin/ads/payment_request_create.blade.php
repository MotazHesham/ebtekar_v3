@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Create Payment Request') }}
        <a class="btn btn-default btn-sm float-right" href="{{ route('admin.ads.accounts.index') }}">
            <i class="fa fa-arrow-left"></i> {{ trans('Back') }}
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ads.payment_requests.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="ad_account_id">{{ trans('Ad Account') }}</label>
                        <select class="form-control {{ $errors->has('ad_account_id') ? 'is-invalid' : '' }}" name="ad_account_id" id="ad_account_id" required>
                            <option value="">{{ trans('Select an account') }}</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('ad_account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }} (ID: {{ $account->id }})</option>
                            @endforeach
                        </select>
                        @if($errors->has('ad_account_id'))
                            <div class="invalid-feedback">{{ $errors->first('ad_account_id') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code">{{ trans('Code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code') }}" placeholder="{{ trans('e.g. REF-001') }}">
                        @if($errors->has('code'))
                            <div class="invalid-feedback">{{ $errors->first('code') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="amount">{{ trans('Amount') }}</label>
                        <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" step="0.01" min="0.01" name="amount" id="amount" value="{{ old('amount') }}" placeholder="0.00" required>
                        @if($errors->has('amount'))
                            <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="add_date">{{ trans('Date') }}</label>
                        <input class="form-control {{ $errors->has('add_date') ? 'is-invalid' : '' }}" type="datetime-local" name="add_date" id="add_date" value="{{ old('add_date', date('Y-m-d\TH:i')) }}" required>
                        @if($errors->has('add_date'))
                            <div class="invalid-feedback">{{ $errors->first('add_date') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">{{ trans('Create') }}</button>
                <a class="btn btn-default" href="{{ route('admin.ads.accounts.index') }}">{{ trans('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
