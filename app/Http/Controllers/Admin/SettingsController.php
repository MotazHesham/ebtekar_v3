<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;

class SettingsController extends Controller
{
    use MediaUploadingTrait;
    public function index()
    {
        abort_if(Gate::denies('setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = Setting::with(['media'])->orderBy('order_level')->get()->groupBy('group_name');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, Setting $setting)
    {
        foreach ($request->all() as $key => $value) {
            $setting = Setting::where('key', $key)->where('lang', $request->lang)->first();
            if (!$setting) {
                $setting = Setting::where('key', $key)->first();
            }

            if ($setting) {
                if ($setting->data_type == 'file' && $request->input($setting->key)) {
                    if ($request->input($setting->key, false)) {
                        if (! $setting->file || $request->input($setting->key) !== $setting->file->file_name) {
                            if ($setting->file) {
                                $setting->file->delete();
                            }
                            $setting->addMedia(storage_path('tmp/uploads/' . basename($request->input($setting->key))))->toMediaCollection('file');
                        }
                    } elseif ($setting->file) {
                        $setting->file->delete();
                    }
                } else {
                    if ($setting->data_type == 'multiselect') {
                        $setting->value = implode(',', $value);
                    } else {
                        $setting->value = $value;
                    }
                    $setting->save();
                }
            }
        }

        foreach (Setting::where('data_type', 'multiselect')->get() as $setting) {
            if (!$request->input($setting->key)) {
                $setting->value = null;
                $setting->save();
            }
        }
        Cache::forget('business_settings');


        toast(trans('flash.updated'), 'success');
        return redirect()->route('admin.settings.index');
    }
}
