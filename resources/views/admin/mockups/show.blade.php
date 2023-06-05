@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.mockup.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mockups.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.id') }}
                        </th>
                        <td>
                            {{ $mockup->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.name') }}
                        </th>
                        <td>
                            {{ $mockup->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.description') }}
                        </th>
                        <td>
                            {{ $mockup->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.preview_1') }}
                        </th>
                        <td>
                            @if($mockup->preview_1)
                                <a href="{{ $mockup->preview_1->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $mockup->preview_1->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.preview_2') }}
                        </th>
                        <td>
                            @if($mockup->preview_2)
                                <a href="{{ $mockup->preview_2->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $mockup->preview_2->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.preview_3') }}
                        </th>
                        <td>
                            @if($mockup->preview_3)
                                <a href="{{ $mockup->preview_3->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $mockup->preview_3->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.video_provider') }}
                        </th>
                        <td>
                            {{ $mockup->video_provider }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.video_link') }}
                        </th>
                        <td>
                            {{ $mockup->video_link }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.purchase_price') }}
                        </th>
                        <td>
                            {{ $mockup->purchase_price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.category') }}
                        </th>
                        <td>
                            {{ $mockup->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.sub_category') }}
                        </th>
                        <td>
                            {{ $mockup->sub_category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.mockup.fields.sub_sub_category') }}
                        </th>
                        <td>
                            {{ $mockup->sub_sub_category->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.mockups.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection