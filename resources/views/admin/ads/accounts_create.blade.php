@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Create Ad Account') }}
        <a class="btn btn-default btn-sm float-right" href="{{ route('admin.ads.accounts.index') }}">
            <i class="fa fa-arrow-left"></i> {{ trans('Back') }}
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.ads.accounts.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="name">{{ trans('Account Name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="{{ trans('e.g. North America - Meta') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="balance">{{ trans('Balance') }}</label>
                        <input class="form-control {{ $errors->has('balance') ? 'is-invalid' : '' }}" type="number" step="0.01" name="balance" id="balance" value="{{ old('balance', 0) }}">
                        @if($errors->has('balance'))
                            <div class="invalid-feedback">{{ $errors->first('balance') }}</div>
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
