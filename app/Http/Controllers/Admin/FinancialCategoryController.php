<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFinancialCategoryRequest;
use App\Http\Requests\StoreFinancialCategoryRequest;
use App\Http\Requests\UpdateFinancialCategoryRequest;
use App\Models\FinancialCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinancialCategoryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('financial_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $financialCategories = FinancialCategory::all();

        return view('admin.financialCategories.index', compact('financialCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('financial_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialCategories.create');
    }

    public function store(StoreFinancialCategoryRequest $request)
    {
        $financialCategory = FinancialCategory::create($request->all());

        return redirect()->route('admin.financial-categories.index');
    }

    public function edit(FinancialCategory $financialCategory)
    {
        abort_if(Gate::denies('financial_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialCategories.edit', compact('financialCategory'));
    }

    public function update(UpdateFinancialCategoryRequest $request, FinancialCategory $financialCategory)
    {
        $financialCategory->update($request->all());

        return redirect()->route('admin.financial-categories.index');
    }

    public function show(FinancialCategory $financialCategory)
    {
        abort_if(Gate::denies('financial_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialCategories.show', compact('financialCategory'));
    }

    public function destroy(FinancialCategory $financialCategory)
    {
        abort_if(Gate::denies('financial_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $financialCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinancialCategoryRequest $request)
    {
        $financialCategories = FinancialCategory::find(request('ids'));

        foreach ($financialCategories as $financialCategory) {
            $financialCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
