@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.season.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.seasons.store') }}">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.season.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                    name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="year">{{ __('cruds.season.fields.year') }}</label>
                <input class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}" type="number"
                    name="year" id="year" value="{{ old('year', date('Y')) }}" min="2000" max="2100" required>
                @if($errors->has('year'))
                    <div class="invalid-feedback">{{ $errors->first('year') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="start_date">{{ __('cruds.season.fields.start_date') }}</label>
                <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                    name="start_date" id="start_date" value="{{ old('start_date', '') }}" required>
                @if($errors->has('start_date'))
                    <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="end_date">{{ __('cruds.season.fields.end_date') }}</label>
                <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                    name="end_date" id="end_date" value="{{ old('end_date', '') }}" required>
                @if($errors->has('end_date'))
                    <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">{{ __('global.save') }}</button>
            </div>
        </form>
    </div>
</div>

@endsection
