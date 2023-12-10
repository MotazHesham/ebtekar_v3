<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyHomeCategoryRequest;
use App\Http\Requests\StoreHomeCategoryRequest;
use App\Http\Requests\UpdateHomeCategoryRequest;
use App\Models\Category;
use App\Models\HomeCategory;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class HomeCategoriesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('home_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homeCategories = HomeCategory::with(['category','website'])->get();

        return view('admin.homeCategories.index', compact('homeCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('home_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.homeCategories.create', compact('websites'));
    }

    public function store(StoreHomeCategoryRequest $request)
    {
        $homeCategory = HomeCategory::create($request->all());

        Cache::forget('home_categories');
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.home-categories.index');
    }

    public function edit(HomeCategory $homeCategory)
    {
        abort_if(Gate::denies('home_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::where('website_setting_id',$homeCategory->website_setting_id)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $homeCategory->load('category');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        return view('admin.homeCategories.edit', compact('categories', 'homeCategory','websites'));
    }

    public function update(UpdateHomeCategoryRequest $request, HomeCategory $homeCategory)
    {
        $homeCategory->update($request->all());
        Cache::forget('home_categories');

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.home-categories.index');
    }

    public function show(HomeCategory $homeCategory)
    {
        abort_if(Gate::denies('home_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homeCategory->load('category');

        return view('admin.homeCategories.show', compact('homeCategory'));
    }

    public function destroy(HomeCategory $homeCategory)
    {
        abort_if(Gate::denies('home_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $homeCategory->delete();
        Cache::forget('home_categories');

        alert(trans('flash.deleted'),'','success');

        return 1;
    }

    public function massDestroy(MassDestroyHomeCategoryRequest $request)
    {
        $homeCategories = HomeCategory::find(request('ids'));

        foreach ($homeCategories as $homeCategory) {
            $homeCategory->delete();
        }
        Cache::forget('home_categories');

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
