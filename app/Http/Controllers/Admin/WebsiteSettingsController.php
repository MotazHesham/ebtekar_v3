<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreWebsiteSettingRequest;
use App\Http\Requests\UpdateWebsiteSettingRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class WebsiteSettingsController extends Controller
{
    use MediaUploadingTrait;

    public function get_categories_by_website(Request $request){
        return Category::where('website_setting_id',$request->website_setting_id)->get();    
    }
    public function get_sub_categories_by_website(Request $request){
        return SubCategory::where('website_setting_id',$request->website_setting_id)->get();    
    }

    public function index()
    {
        abort_if(Gate::denies('website_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websiteSettings = WebsiteSetting::with(['designer', 'preparer', 'manufacturer', 'shipment', 'media'])->get();

        return view('admin.websiteSettings.index', compact('websiteSettings'));
    }


    public function create()
    {
        abort_if(Gate::denies('website_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $preparers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $manufacturers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $shipments = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), ''); 

        return view('admin.websiteSettings.create', compact('designers', 'manufacturers', 'preparers', 'shipments'));
    }
    
    public function store(StoreWebsiteSettingRequest $request)
    { 
        
        $validated_request = $request->all();
        $validated_request['domains'] = implode('|',$request->domains);  
        $validated_request['keywords_seo'] = implode('|',$request->keywords_seo);  
        $websiteSetting = WebsiteSetting::create($validated_request);
        
        if ($request->input('logo', false)) {
            if (! $websiteSetting->logo || $request->input('logo') !== $websiteSetting->logo->file_name) {
                if ($websiteSetting->logo) {
                    $websiteSetting->logo->delete();
                }
                $websiteSetting->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($websiteSetting->logo) {
            $websiteSetting->logo->delete();
        }

        if (count($websiteSetting->photos) > 0) {
            foreach ($websiteSetting->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $websiteSetting->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $websiteSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        toast(__('flash.global.success_title'),'success');
        return redirect()->route('admin.website-settings.index');
    }


    public function edit(WebsiteSetting $websiteSetting)
    {
        abort_if(Gate::denies('website_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $preparers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $manufacturers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $shipments = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $reviewers = User::where('user_type','staff')->get()->pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $websiteSetting->load('designer', 'preparer', 'manufacturer', 'shipment', 'reviewer');

        return view('admin.websiteSettings.edit', compact('designers', 'websiteSetting', 'manufacturers', 'preparers', 'shipments', 'reviewers'));
    }

    public function update(UpdateWebsiteSettingRequest $request, WebsiteSetting $websiteSetting)
    {
        $validated_request = $request->all();
        $validated_request['domains'] = implode('|',$request->domains);  
        $validated_request['keywords_seo'] = implode('|',$request->keywords_seo);   
        $websiteSetting->update($validated_request);

        if ($request->input('logo', false)) {
            if (! $websiteSetting->logo || $request->input('logo') !== $websiteSetting->logo->file_name) {
                if ($websiteSetting->logo) {
                    $websiteSetting->logo->delete();
                }
                $websiteSetting->addMedia(storage_path('tmp/uploads/' . basename($request->input('logo'))))->toMediaCollection('logo');
            }
        } elseif ($websiteSetting->logo) {
            $websiteSetting->logo->delete();
        }

        if (count($websiteSetting->photos) > 0) {
            foreach ($websiteSetting->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $websiteSetting->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $websiteSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        toast(__('flash.global.update_title'),'success');
        return redirect()->route('admin.website-settings.index');
    }

    public function show(WebsiteSetting $websiteSetting)
    {
        abort_if(Gate::denies('website_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websiteSetting->load('designer', 'preparer', 'manufacturer', 'shipment');

        return view('admin.websiteSettings.show', compact('websiteSetting'));
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('website_setting_create') && Gate::denies('website_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new WebsiteSetting();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
