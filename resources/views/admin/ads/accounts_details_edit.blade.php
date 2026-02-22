@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Edit Account Detail') }} — {{ $selectedAccount->name }} / {{ $accountDetail->name }}
        <a class="btn btn-default btn-sm float-right" href="{{ route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id]) }}">
            <i class="fa fa-arrow-left"></i> {{ trans('Back') }}
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ads.accounts.details.update', [$selectedAccount->id, $accountDetail->id]) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ trans('Detail Name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $accountDetail->name) }}" placeholder="{{ trans('e.g. Meta - US') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="utm_key">{{ trans('UTM Key') }}</label>
                        <input class="form-control {{ $errors->has('utm_key') ? 'is-invalid' : '' }}" type="text" name="utm_key" id="utm_key" value="{{ old('utm_key', $accountDetail->utm_key) }}" placeholder="{{ trans('e.g. utm_source') }}">
                        @if($errors->has('utm_key'))
                            <div class="invalid-feedback">{{ $errors->first('utm_key') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="type">{{ trans('Type') }}</label>
                        <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                            <option value="">{{ trans('Select type') }}</option>
                            <option value="campaign" {{ old('type', $accountDetail->type) == 'campaign' ? 'selected' : '' }}>{{ trans('Campaign') }}</option>
                            <option value="ad_set" {{ old('type', $accountDetail->type) == 'ad_set' ? 'selected' : '' }}>{{ trans('Ad Set') }}</option>
                            <option value="ad" {{ old('type', $accountDetail->type) == 'ad' ? 'selected' : '' }}>{{ trans('Ad') }}</option>
                        </select>
                        @if($errors->has('type'))
                            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="parent_id">{{ trans('Parent Detail (optional)') }}</label>
                        <select class="form-control {{ $errors->has('parent_id') ? 'is-invalid' : '' }}" name="parent_id" id="parent_id">
                            <option value="">{{ trans('No parent') }}</option>
                            @foreach(($parentOptions ?? collect()) as $opt)
                                @php
                                    $typeLabel = $opt->type ? ucfirst(str_replace('_', ' ', $opt->type)) : '—';
                                    $parentPart = $opt->parent ? ' — ' . trans('Parent') . ': ' . $opt->parent->name : '';
                                @endphp
                                <option value="{{ $opt->id }}" {{ (string)old('parent_id', $accountDetail->parent_id) === (string)$opt->id ? 'selected' : '' }}>{{ $opt->name }} ({{ $typeLabel }}){{ $parentPart }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('parent_id'))
                            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">{{ trans('Update') }}</button>
                <a class="btn btn-default" href="{{ route('admin.ads.accounts.index', ['account_id' => $selectedAccount->id]) }}">{{ trans('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
