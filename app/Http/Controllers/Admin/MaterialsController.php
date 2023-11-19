<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyMaterialRequest;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Material;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaterialsController extends Controller
{
    public function stock(Request $request){
        if($request->type == 'in'){
            $material = Material::find($request->model_id);
            Expense::create([ 
                'expense_category_id' => 2,
                'entry_date' => $request->entry_date,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'model_id' => $request->model_id,
                'model_type' => 'App\Models\Material',
            ]);
            $material->remaining += $request->quantity;
            $material->save();

        }elseif($request->type == 'out'){
            $material = Material::find($request->model_id);
            Income::create([ 
                'income_category_id' => 5,
                'entry_date' => $request->entry_date,
                'amount' => $request->amount,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'model_id' => $request->model_id,
                'model_type' => 'App\Models\Material',
            ]);
            $material->remaining -= $request->quantity;
            $material->save();
        }

        return redirect()->route('admin.materials.show',$material->id);
    }

    public function index()
    {
        abort_if(Gate::denies('material_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $materials = Material::all();

        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        abort_if(Gate::denies('material_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.materials.create');
    }

    public function store(StoreMaterialRequest $request)
    {
        $material = Material::create($request->all());

        return redirect()->route('admin.materials.index');
    }

    public function edit(Material $material)
    {
        abort_if(Gate::denies('material_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.materials.edit', compact('material'));
    }

    public function update(UpdateMaterialRequest $request, Material $material)
    {
        $material->update($request->all());

        return redirect()->route('admin.materials.index');
    }

    public function show(Material $material)
    {
        abort_if(Gate::denies('material_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $material->load('incomes','expenses'); 
        return view('admin.materials.show', compact('material'));
    }

    public function destroy(Material $material)
    {
        abort_if(Gate::denies('material_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $material->delete();

        return back();
    }

    public function massDestroy(MassDestroyMaterialRequest $request)
    {
        $materials = Material::find(request('ids'));

        foreach ($materials as $material) {
            $material->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
