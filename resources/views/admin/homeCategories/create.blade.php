@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.homeCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.home-categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="website_setting_id">{{ trans('global.extra.website_setting_id') }}</label>
                <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}" onchange="get_categories_by_website()" name="website_setting_id" id="website_setting_id" required>
                    @foreach($websites as $id => $entry)
                        <option value="{{ $id }}" {{ old('website_setting_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('website_setting_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('website_setting_id') }}
                    </div>
                @endif 
            </div>
            <div class="form-group">
                <label class="required" for="category_id">{{ trans('cruds.homeCategory.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    {{-- ajax call --}}
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.homeCategory.fields.category_helper') }}</span>
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