@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.seoSetting.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.seo-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.seoSetting.fields.id') }}
                        </th>
                        <td>
                            {{ $seoSetting->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seoSetting.fields.keyword') }}
                        </th>
                        <td>
                            {{ $seoSetting->keyword }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seoSetting.fields.author') }}
                        </th>
                        <td>
                            {{ $seoSetting->author }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seoSetting.fields.sitremap_link') }}
                        </th>
                        <td>
                            {{ $seoSetting->sitremap_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seoSetting.fields.description') }}
                        </th>
                        <td>
                            {{ $seoSetting->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.seo-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection