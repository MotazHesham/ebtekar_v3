@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.designe.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.designes.update", [$designe->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="design_name">{{ trans('cruds.designe.fields.design_name') }}</label>
                <input class="form-control {{ $errors->has('design_name') ? 'is-invalid' : '' }}" type="text" name="design_name" id="design_name" value="{{ old('design_name', $designe->design_name) }}" required>
                @if($errors->has('design_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('design_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.design_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="profit">{{ trans('cruds.designe.fields.profit') }}</label>
                <input class="form-control {{ $errors->has('profit') ? 'is-invalid' : '' }}" type="number" name="profit" id="profit" value="{{ old('profit', $designe->profit) }}" step="0.01" required>
                @if($errors->has('profit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.profit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.designe.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Designe::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $designe->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cancel_reason">{{ trans('cruds.designe.fields.cancel_reason') }}</label>
                <textarea class="form-control {{ $errors->has('cancel_reason') ? 'is-invalid' : '' }}" name="cancel_reason" id="cancel_reason">{{ old('cancel_reason', $designe->cancel_reason) }}</textarea>
                @if($errors->has('cancel_reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cancel_reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.cancel_reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.designe.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $designe->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mockup_id">{{ trans('cruds.designe.fields.mockup') }}</label>
                <select class="form-control select2 {{ $errors->has('mockup') ? 'is-invalid' : '' }}" name="mockup_id" id="mockup_id" required>
                    @foreach($mockups as $id => $entry)
                        <option value="{{ $id }}" {{ (old('mockup_id') ? old('mockup_id') : $designe->mockup->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mockup'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mockup') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.designe.fields.mockup_helper') }}</span>
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