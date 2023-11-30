@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title"
                        id="title" value="{{ old('title', '') }}" required>
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="card" style="background: #ffffff61;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                            <li class="nav-item">
                                @foreach (\App\Models\Permission::where('parent', 1)->get() as $parent)
                                    <a class="nav-link @if ($loop->first) active @endif"
                                        href="#{{ $parent->id }}" role="tab" data-toggle="tab">
                                        {{ trans('permissions.' . $parent->title) }}
                                    </a>
                                @endforeach
                                <a class="nav-link" href="#general" role="tab" data-toggle="tab">
                                    أخري
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-10">
                        <div class="tab-content">
                            @foreach (\App\Models\Permission::where('parent', 1)->get() as $parent)
                                <div class="tab-pane @if ($loop->first) active @endif" role="tabpanel"
                                    id="{{ $parent->id }}">
                                    <div style="display: flex;justify-content: flex-start;padding: 20px;">
                                        <label for="p{{ $parent->id }}"
                                            style="padding: 0px 20px;">{{ trans('permissions.' . $parent->title) }}</label>
                                        <label class="c-switch c-switch-pill c-switch-success">
                                            <input name="permission[]" value="{{ $parent->id }}"
                                                id="p{{ $parent->id }}" type="checkbox" class="c-switch-input">
                                            <span class="c-switch-slider"></span>
                                        </label>
                                    </div>
                                    <div class="row">
                                        @foreach (\App\Models\Permission::whereIn('type', explode('.', $parent->type))->get()->groupBy('type') as $key => $array)
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-header">{{ trans('permissions.type.'.$key) }}</div>
                                                    <div class="card-body">
                                                        @foreach ($array as $raw)
                                                            <div style="display: flex;justify-content: space-evenly">
                                                                <label
                                                                    for="{{ $raw->id }}">{{ trans('permissions.' . $raw->title) }}</label>
                                                                <label class="c-switch c-switch-pill c-switch-success">
                                                                    <input name="permissions[]" value="{{ $raw->id }}"
                                                                        id="{{ $raw->id }}" type="checkbox"
                                                                        class="c-switch-input">
                                                                    <span class="c-switch-slider"></span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="tab-pane" role="tabpanel" id="general">
                                <div class="row">
                                    @foreach (\App\Models\Permission::where('type', 'general')->get()->groupBy('type') as $key => $array)
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-header">{{ trans('permissions.type.'.$key) }}</div>
                                                <div class="card-body">
                                                    @foreach ($array as $raw)
                                                        <div style="display: flex;justify-content: space-evenly">
                                                            <span>{{ trans('permissions.' . $raw->title) }}</span>
                                                            <label class="c-switch c-switch-pill c-switch-success">
                                                                <input name="permissions[]" value="{{ $raw->id }}"
                                                                    type="checkbox" class="c-switch-input">
                                                                <span class="c-switch-slider"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>

    </form>
@endsection
