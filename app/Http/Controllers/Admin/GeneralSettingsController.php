<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\UpdateGeneralSettingRequest;
use App\Models\GeneralSetting;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class GeneralSettingsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('general_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $generalSettings = GeneralSetting::with(['designer', 'preparer', 'manufacturer', 'shipment', 'media'])->get();

        return view('admin.generalSettings.index', compact('generalSettings'));
    }

    public function edit(GeneralSetting $generalSetting)
    {
        abort_if(Gate::denies('general_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preparers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $manufacturers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipments = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $generalSetting->load('designer', 'preparer', 'manufacturer', 'shipment');

        return view('admin.generalSettings.edit', compact('designers', 'generalSetting', 'manufacturers', 'preparers', 'shipments'));
    }

    public function update(UpdateGeneralSettingRequest $request, GeneralSetting $generalSetting)
    {
        $generalSetting->update($request->all());

        if ($request->input('logo', false)) {
            if (! $generalSetting->logo || $request->input('logo') !== $generalSetting->logo->file_name) {
                if ($generalSetting->logo) {
                    $generalSetting->logo->delete();
                }
                $generalSetting->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($generalSetting->logo) {
            $generalSetting->logo->delete();
        }

        if (count($generalSetting->photos) > 0) {
            foreach ($generalSetting->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $generalSetting->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $generalSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.general-settings.index');
    }

    public function show(GeneralSetting $generalSetting)
    {
        abort_if(Gate::denies('general_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $generalSetting->load('designer', 'preparer', 'manufacturer', 'shipment');

        return view('admin.generalSettings.show', compact('generalSetting'));
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('general_setting_create') && Gate::denies('general_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new GeneralSetting();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
