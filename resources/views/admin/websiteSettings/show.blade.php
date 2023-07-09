@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.websiteSetting.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.website-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.id') }}
                        </th>
                        <td>
                            {{ $websiteSetting->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.logo') }}
                        </th>
                        <td>
                            @if($websiteSetting->logo)
                                <a href="{{ $websiteSetting->logo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $websiteSetting->logo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.site_name') }}
                        </th>
                        <td>
                            {{ $websiteSetting->site_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.address') }}
                        </th>
                        <td>
                            {{ $websiteSetting->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.description') }}
                        </th>
                        <td>
                            {{ $websiteSetting->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.phone_number') }}
                        </th>
                        <td>
                            {{ $websiteSetting->phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.email') }}
                        </th>
                        <td>
                            {{ $websiteSetting->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.facebook') }}
                        </th>
                        <td>
                            {{ $websiteSetting->facebook }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.instagram') }}
                        </th>
                        <td>
                            {{ $websiteSetting->instagram }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.twitter') }}
                        </th>
                        <td>
                            {{ $websiteSetting->twitter }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.telegram') }}
                        </th>
                        <td>
                            {{ $websiteSetting->telegram }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.linkedin') }}
                        </th>
                        <td>
                            {{ $websiteSetting->linkedin }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.whatsapp') }}
                        </th>
                        <td>
                            {{ $websiteSetting->whatsapp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.youtube') }}
                        </th>
                        <td>
                            {{ $websiteSetting->youtube }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.google_plus') }}
                        </th>
                        <td>
                            {{ $websiteSetting->google_plus }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.welcome_message') }}
                        </th>
                        <td>
                            {{ $websiteSetting->welcome_message }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.photos') }}
                        </th>
                        <td>
                            @foreach($websiteSetting->photos as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.video_instructions') }}
                        </th>
                        <td>
                            {{ $websiteSetting->video_instructions }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.delivery_system') }}
                        </th>
                        <td>
                            {{ App\Models\WebsiteSetting::DELIVERY_SYSTEM_SELECT[$websiteSetting->delivery_system] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.designer') }}
                        </th>
                        <td>
                            {{ $websiteSetting->designer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.preparer') }}
                        </th>
                        <td>
                            {{ $websiteSetting->preparer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.manufacturer') }}
                        </th>
                        <td>
                            {{ $websiteSetting->manufacturer->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.websiteSetting.fields.shipment') }}
                        </th>
                        <td>
                            {{ $websiteSetting->shipment->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.website-settings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection