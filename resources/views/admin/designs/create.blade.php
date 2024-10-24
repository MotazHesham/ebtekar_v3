@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.design.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.designs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="design_name">{{ __('cruds.design.fields.design_name') }}</label>
                <input class="form-control {{ $errors->has('design_name') ? 'is-invalid' : '' }}" type="text" name="design_name" id="design_name" value="{{ old('design_name', '') }}" required>
                @if($errors->has('design_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('design_name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.design.fields.design_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="profit">{{ __('cruds.design.fields.profit') }}</label>
                <input class="form-control {{ $errors->has('profit') ? 'is-invalid' : '' }}" type="number" name="profit" id="profit" value="{{ old('profit', '') }}" step="0.01" required>
                @if($errors->has('profit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profit') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.design.fields.profit_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ __('cruds.design.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ __('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Designe::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.design.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ __('cruds.design.fields.user') }}</label>
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
                <span class="help-block">{{ __('cruds.design.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mockup_id">{{ __('cruds.design.fields.mockup') }}</label>
                <select class="form-control select2 {{ $errors->has('mockup') ? 'is-invalid' : '' }}" name="mockup_id" id="mockup_id" required>
                    @foreach($mockups as $id => $entry)
                        <option value="{{ $id }}" {{ old('mockup_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('mockup'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mockup') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.design.fields.mockup_helper') }}</span>
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