<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubSubCategoryRequest;
use App\Http\Requests\StoreSubSubCategoryRequest;
use App\Http\Requests\UpdateSubSubCategoryRequest;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('sub_sub_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = SubSubCategory::with(['sub_category'])->select(sprintf('%s.*', (new SubSubCategory)->table));
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
            $table->editColumn('slug', function ($row) {
                return $row->slug ? $row->slug : '';
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

            $table->rawColumns(['actions', 'placeholder', 'sub_category']);

            return $table->make(true);
        }

        return view('admin.subSubCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sub_sub_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sub_categories = SubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.subSubCategories.create', compact('sub_categories'));
    }

    public function store(StoreSubSubCategoryRequest $request)
    {
        $subSubCategory = SubSubCategory::create($request->all());

        return redirect()->route('admin.sub-sub-categories.index');
    }

    public function edit(SubSubCategory $subSubCategory)
    {
        abort_if(Gate::denies('sub_sub_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sub_categories = SubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subSubCategory->load('sub_category');

        return view('admin.subSubCategories.edit', compact('subSubCategory', 'sub_categories'));
    }

    public function update(UpdateSubSubCategoryRequest $request, SubSubCategory $subSubCategory)
    {
        $subSubCategory->update($request->all());

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

        return back();
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
