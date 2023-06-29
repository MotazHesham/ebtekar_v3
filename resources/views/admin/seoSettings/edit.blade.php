@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.seoSetting.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.seo-settings.update", [$seoSetting->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="keyword">{{ trans('cruds.seoSetting.fields.keyword') }}</label>
                <input class="form-control {{ $errors->has('keyword') ? 'is-invalid' : '' }}" type="text" name="keyword[]" id="keyword" value="{{ $seoSetting->keyword }}"  placeholder="add tags ..." data-role="tagsinput" required>
                @if($errors->has('keyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('keyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.seoSetting.fields.keyword_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="author">{{ trans('cruds.seoSetting.fields.author') }}</label>
                <input class="form-control {{ $errors->has('author') ? 'is-invalid' : '' }}" type="text" name="author" id="author" value="{{ old('author', $seoSetting->author) }}" required>
                @if($errors->has('author'))
                    <div class="invalid-feedback">
                        {{ $errors->first('author') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.seoSetting.fields.author_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sitremap_link">{{ trans('cruds.seoSetting.fields.sitremap_link') }}</label>
                <input class="form-control {{ $errors->has('sitremap_link') ? 'is-invalid' : '' }}" type="text" name="sitremap_link" id="sitremap_link" value="{{ old('sitremap_link', $seoSetting->sitremap_link) }}" required>
                @if($errors->has('sitremap_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sitremap_link') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.seoSetting.fields.sitremap_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.seoSetting.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" required>{{ old('description', $seoSetting->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.seoSetting.fields.description_helper') }}</span>
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