@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.bannedPhone.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.banned-phones.update", [$bannedPhone->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="phone">{{ __('cruds.bannedPhone.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $bannedPhone->phone) }}" required>
                @if($errors->has('phone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.bannedPhone.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reason">{{ __('cruds.bannedPhone.fields.reason') }}</label>
                <textarea class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason" required>{{ old('reason', $bannedPhone->reason) }}</textarea>
                @if($errors->has('reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reason') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.bannedPhone.fields.reason_helper') }}</span>
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