<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubCategoryRequest;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\WebsiteSetting;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    
    public function update_statuses(Request $request){ 
        $type = $request->type;
        $subcategory = SubCategory::findOrFail($request->id);
        $subcategory->$type = $request->status; 
        $subcategory->save();
        Cache::forget('header_nested_categories_'.$subcategory->website_setting_id);
        return 1;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SubCategory::with(['category'])->select(sprintf('%s.*', (new SubCategory)->table))->with('website');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sub_category_show';
                $editGate      = 'sub_category_edit';
                $deleteGate    = 'sub_category_delete';
                $crudRoutePart = 'sub-categories';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->editColumn('website_site_name', function ($row) { 
                return $row->website->site_name ?? '';
            });
            $table->editColumn('published', function ($row) { 
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'published\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->published ? "checked" : null) .' }}>
                    <span class="c-switch-slider"></span>
                </label>';
            }); 
            $table->rawColumns(['actions', 'placeholder','published', 'category','website_site_name']);

            return $table->make(true);
        }

        return view('admin.subCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sub_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subCategories.create', compact(  'websites'));
    }

    public function store(StoreSubCategoryRequest $request)
    { 
        $validated_request = $request->all();
        $validated_request['slug'] = preg_replace('/([^ا-يA-Za-z0-9\-])(uml|acute|grave|circ|tilde|ring)/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        $subCategory = SubCategory::create($validated_request);

        Cache::forget('header_nested_categories_'.$subCategory->website_setting_id);
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.sub-categories.index');
    }

    public function edit(SubCategory $subCategory)
    {
        abort_if(Gate::denies('sub_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::where('website_setting_id',$subCategory->website_setting_id)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subCategory->load('category');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        return view('admin.subCategories.edit', compact('categories', 'subCategory','websites'));
    }

    public function update(UpdateSubCategoryRequest $request, SubCategory $subCategory)
    {
        $subCategory->update($request->all());

        Cache::forget('header_nested_categories_'.$subCategory->website_setting_id);
        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.sub-categories.index');
    }

    public function show(SubCategory $subCategory)
    {
        abort_if(Gate::denies('sub_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subCategory->load('category');

        return view('admin.subCategories.show', compact('subCategory'));
    }

    public function destroy(SubCategory $subCategory)
    {
        abort_if(Gate::denies('sub_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subCategory->delete();

        Cache::forget('header_nested_categories_'.$subCategory->website_setting_id);
        alert(trans('flash.deleted'),'','success');
        return 1;
    }

    public function massDestroy(MassDestroySubCategoryRequest $request)
    {
        $subCategories = SubCategory::find(request('ids'));

        foreach ($subCategories as $subCategory) {
            $subCategory->delete();
            Cache::forget('header_nested_categories_'.$subCategory->website_setting_id);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
