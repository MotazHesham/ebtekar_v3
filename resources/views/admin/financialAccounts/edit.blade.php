@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.financialAccount.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.financial-accounts.update", [$financialAccount->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="account">{{ __('cruds.financialAccount.fields.account') }}</label>
                <input class="form-control {{ $errors->has('account') ? 'is-invalid' : '' }}" type="text" name="account" id="account" value="{{ old('account', $financialAccount->account) }}" required>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.financialAccount.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ __('cruds.financialAccount.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $financialAccount->description) }}">
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.financialAccount.fields.description_helper') }}</span>
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