<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubSubCategoryRequest;
use App\Http\Requests\StoreSubSubCategoryRequest;
use App\Http\Requests\UpdateSubSubCategoryRequest;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SubSubCategoryController extends Controller
{
    
    public function update_statuses(Request $request){ 
        $type = $request->type;
        $subsubcategory = SubSubCategory::findOrFail($request->id);
        $subsubcategory->$type = $request->status; 
        $subsubcategory->save();
        return 1;
    }
    public function index(Request $request)
    {
        abort_if(Gate::denies('sub_sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SubSubCategory::with(['sub_category'])->select(sprintf('%s.*', (new SubSubCategory)->table))->with('website');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'sub_sub_category_show';
                $editGate      = 'sub_sub_category_edit';
                $deleteGate    = 'sub_sub_category_delete';
                $crudRoutePart = 'sub-sub-categories';

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
            $table->editColumn('meta_title', function ($row) {
                return $row->meta_title ? $row->meta_title : '';
            });
            $table->editColumn('meta_description', function ($row) {
                return $row->meta_description ? $row->meta_description : '';
            });
            $table->addColumn('sub_category_name', function ($row) {
                return $row->sub_category ? $row->sub_category->name : '';
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
            $table->rawColumns(['actions', 'placeholder','published', 'sub_category','website_site_name']);

            return $table->make(true);
        }

        return view('admin.subSubCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sub_sub_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('admin.subSubCategories.create', compact( 'websites'));
    }

    public function store(StoreSubSubCategoryRequest $request)
    { 
        $validated_request = $request->all();
        $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        $subSubCategory = SubSubCategory::create($validated_request);

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.sub-sub-categories.index');
    }

    public function edit(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sub_categories = SubCategory::where('website_setting_id',$subSubCategory->website_setting_id)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subSubCategory->load('sub_category');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        return view('admin.subSubCategories.edit', compact('subSubCategory', 'sub_categories','websites'));
    }

    public function update(UpdateSubSubCategoryRequest $request, SubSubCategory $subSubCategory)
    {
        $subSubCategory->update($request->all());

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.sub-sub-categories.index');
    }

    public function show(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subSubCategory->load('sub_category');

        return view('admin.subSubCategories.show', compact('subSubCategory'));
    }

    public function destroy(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subSubCategory->delete();

        alert(trans('flash.deleted'),'','success');
        return 1;
    }

    public function massDestroy(MassDestroySubSubCategoryRequest $request)
    {
        $subSubCategories = SubSubCategory::find(request('ids'));

        foreach ($subSubCategories as $subSubCategory) {
            $subSubCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
