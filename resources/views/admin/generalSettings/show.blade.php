@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.generalSetting.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.general-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.id') }}
                        </th>
                        <td>
                            {{ $generalSetting->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.logo') }}
                        </th>
                        <td>
                            @if($generalSetting->logo)
                                <a href="{{ $generalSetting->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $generalSetting->logo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.site_name') }}
                        </th>
                        <td>
                            {{ $generalSetting->site_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.address') }}
                        </th>
                        <td>
                            {{ $generalSetting->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.description') }}
                        </th>
                        <td>
                            {{ $generalSetting->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $generalSetting->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.email') }}
                        </th>
                        <td>
                            {{ $generalSetting->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.facebook') }}
                        </th>
                        <td>
                            {{ $generalSetting->facebook }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.instagram') }}
                        </th>
                        <td>
                            {{ $generalSetting->instagram }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.twitter') }}
                        </th>
                        <td>
                            {{ $generalSetting->twitter }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.telegram') }}
                        </th>
                        <td>
                            {{ $generalSetting->telegram }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.linkedin') }}
                        </th>
                        <td>
                            {{ $generalSetting->linkedin }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.whatsapp') }}
                        </th>
                        <td>
                            {{ $generalSetting->whatsapp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.youtube') }}
                        </th>
                        <td>
                            {{ $generalSetting->youtube }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.google_plus') }}
                        </th>
                        <td>
                            {{ $generalSetting->google_plus }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.welcome_message') }}
                        </th>
                        <td>
                            {{ $generalSetting->welcome_message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.photos') }}
                        </th>
                        <td>
                            @foreach($generalSetting->photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.video_instructions') }}
                        </th>
                        <td>
                            {{ $generalSetting->video_instructions }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.delivery_system') }}
                        </th>
                        <td>
                            {{ App\Models\GeneralSetting::DELIVERY_SYSTEM_SELECT[$generalSetting->delivery_system] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.designer') }}
                        </th>
                        <td>
                            {{ $generalSetting->designer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.preparer') }}
                        </th>
                        <td>
                            {{ $generalSetting->preparer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.manufacturer') }}
                        </th>
                        <td>
                            {{ $generalSetting->manufacturer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.generalSetting.fields.shipment') }}
                        </th>
                        <td>
                            {{ $generalSetting->shipment->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.general-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection