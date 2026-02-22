@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Add Campaign') }}
        <a class="btn btn-default btn-sm float-right" href="{{ route('admin.ads.accounts.assign-campaign') }}">
            <i class="fa fa-arrow-left"></i> {{ trans('Back') }}
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ads.accounts.details.store-standalone') }}">
            @csrf
            <input type="hidden" name="type" value="campaign">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ trans('Detail Name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="{{ trans('e.g. Meta - US') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="utm_key">{{ trans('UTM Key') }}</label>
                        <input class="form-control {{ $errors->has('utm_key') ? 'is-invalid' : '' }}" type="text" name="utm_key" id="utm_key" value="{{ old('utm_key') }}" placeholder="{{ trans('e.g. utm_source') }}">
                        @if($errors->has('utm_key'))
                            <div class="invalid-feedback">{{ $errors->first('utm_key') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="account_id">{{ trans('Account') }}</label>
                        <select class="form-control {{ $errors->has('account_id') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                            <option value="">{{ trans('Select account') }}</option>
                            @foreach(($accounts ?? collect()) as $acc)
                                <option value="{{ $acc->id }}" {{ (string)old('account_id') === (string)$acc->id ? 'selected' : '' }}>{{ $acc->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('account_id'))
                            <div class="invalid-feedback">{{ $errors->first('account_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">{{ trans('Create') }}</button>
                <a class="btn btn-default" href="{{ route('admin.ads.accounts.assign-campaign') }}">{{ trans('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
