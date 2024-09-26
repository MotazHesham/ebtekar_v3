@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.subCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sub-categories.update", [$subCategory->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="id" value="{{$subCategory->id}}">
            <div class="form-group">
                <label class="required" for="name">{{ __('cruds.subCategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $subCategory->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subCategory.fields.name_helper') }}</span>
            </div> 
            <div class="form-group">
                <label for="slug">{{ __('cruds.subCategory.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $subCategory->slug) }}">
                @if($errors->has('slug'))
                    <div class="invalid-feedback">
                        {{ $errors->first('slug') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subCategory.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_title">{{ __('cruds.subCategory.fields.meta_title') }}</label>
                <input class="form-control {{ $errors->has('meta_title') ? 'is-invalid' : '' }}" type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $subCategory->meta_title) }}">
                @if($errors->has('meta_title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_title') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subCategory.fields.meta_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="meta_description">{{ __('cruds.subCategory.fields.meta_description') }}</label>
                <input class="form-control {{ $errors->has('meta_description') ? 'is-invalid' : '' }}" type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', $subCategory->meta_description) }}">
                @if($errors->has('meta_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('meta_description') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subCategory.fields.meta_description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="category_id">{{ __('cruds.subCategory.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $subCategory->category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.subCategory.fields.category_helper') }}</span>
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