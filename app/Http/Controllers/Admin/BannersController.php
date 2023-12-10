<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyBannerRequest;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Models\Banner;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class BannersController extends Controller
{
    use MediaUploadingTrait;

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $banner = Banner::findOrFail($request->id);
        $banner->$type = $request->status; 
        $banner->save();
        Cache::forget('home_banners_1_'.$banner->website_setting_id);
        return 1;
    }

    public function index()
    {
        abort_if(Gate::denies('banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banners = Banner::with(['media','website'])->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        abort_if(Gate::denies('banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('admin.banners.create',compact('websites'));
    }

    public function store(StoreBannerRequest $request)
    { 
        $banner = Banner::create($request->all());

        if ($request->input('photo', false)) {
            $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $banner->id]);
        }

        Cache::forget('home_banners_1_'.$banner->website_setting_id);
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.banners.index');
    } 
    public function edit(Banner $banner)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 
        return view('admin.banners.edit', compact('banner','websites'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner->update($request->all());

        if ($request->input('photo', false)) {
            if (! $banner->photo || $request->input('photo') !== $banner->photo->file_name) {
                if ($banner->photo) {
                    $banner->photo->delete();
                }
                $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($banner->photo) {
            $banner->photo->delete();
        }

        Cache::forget('home_banners_1_'.$banner->website_setting_id);
        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.banners.index');
    }

    public function show(Banner $banner)
    {
        abort_if(Gate::denies('banner_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.banners.show', compact('banner'));
    }

    public function destroy(Banner $banner)
    {
        abort_if(Gate::denies('banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner->delete();

        alert(trans('flash.deleted'),'','success');

        Cache::forget('home_banners_1_'.$banner->website_setting_id);
        return 1;
    }

    public function massDestroy(MassDestroyBannerRequest $request)
    {
        $banners = Banner::find(request('ids'));

        foreach ($banners as $banner) {
            $banner->delete();
            Cache::forget('home_banners_1_'.$banner->website_setting_id);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('banner_create') && Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Banner();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
