@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.subSubCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sub-sub-categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="website_setting_id">{{ __('global.extra.website_setting_id') }}</label>
                <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}" onchange="get_sub_categories_by_website()" name="website_setting_id" id="website_setting_id" required>
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
                <label class="required" for="sub_category_id">{{ __('cruds.subSubCategory.fields.sub_category') }}</label>
                <select class="form-control select2 {{ $errors->has('sub_category') ? 'is-invalid' : '' }}" name="sub_category_id" id="sub_category_id" required>
                    {{-- ajax call --}}
                </select>
                @if($errors->has('sub_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sub_category') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subSubCategory.fields.sub_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.subSubCategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subSubCategory.fields.name_helper') }}</span>
            </div> 
            <div class="form-group">
                <label for="meta_title">{{ __('cruds.subSubCategory.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', '') }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subSubCategory.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ __('cruds.subSubCategory.fields.meta_description') }}</label>
                <input class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', '') }}">
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subSubCategory.fields.meta_description_helper') }}</span>
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