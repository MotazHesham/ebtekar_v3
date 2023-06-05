<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySeoSettingRequest;
use App\Http\Requests\StoreSeoSettingRequest;
use App\Http\Requests\UpdateSeoSettingRequest;
use App\Models\SeoSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoSettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('seo_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seoSettings = SeoSetting::all();

        return view('admin.seoSettings.index', compact('seoSettings'));
    }

    public function create()
    {
        abort_if(Gate::denies('seo_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seoSettings.create');
    }

    public function store(StoreSeoSettingRequest $request)
    {
        $seoSetting = SeoSetting::create($request->all());

        return redirect()->route('admin.seo-settings.index');
    }

    public function edit(SeoSetting $seoSetting)
    {
        abort_if(Gate::denies('seo_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seoSettings.edit', compact('seoSetting'));
    }

    public function update(UpdateSeoSettingRequest $request, SeoSetting $seoSetting)
    {
        $seoSetting->update($request->all());

        return redirect()->route('admin.seo-settings.index');
    }

    public function show(SeoSetting $seoSetting)
    {
        abort_if(Gate::denies('seo_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seoSettings.show', compact('seoSetting'));
    }

    public function destroy(SeoSetting $seoSetting)
    {
        abort_if(Gate::denies('seo_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seoSetting->delete();

        return back();
    }

    public function massDestroy(MassDestroySeoSettingRequest $request)
    {
        $seoSettings = SeoSetting::find(request('ids'));

        foreach ($seoSettings as $seoSetting) {
            $seoSetting->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
