<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDesignerRequest;
use App\Http\Requests\StoreDesignerRequest;
use App\Http\Requests\UpdateDesignerRequest;
use App\Models\Designer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DesignersController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('designer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Designer::query()->select(sprintf('%s.*', (new Designer)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'designer_show';
                $editGate      = 'designer_edit';
                $deleteGate    = 'designer_delete';
                $crudRoutePart = 'designers';

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
            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });
            $table->editColumn('store_name', function ($row) {
                return $row->store_name ? $row->store_name : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.designers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('designer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.create');
    }

    public function store(StoreDesignerRequest $request)
    {
        $designer = Designer::create($request->all());

        return redirect()->route('admin.designers.index');
    }

    public function edit(Designer $designer)
    {
        abort_if(Gate::denies('designer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.edit', compact('designer'));
    }

    public function update(UpdateDesignerRequest $request, Designer $designer)
    {
        $designer->update($request->all());

        return redirect()->route('admin.designers.index');
    }

    public function show(Designer $designer)
    {
        abort_if(Gate::denies('designer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.show', compact('designer'));
    }

    public function destroy(Designer $designer)
    {
        abort_if(Gate::denies('designer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designer->delete();

        return back();
    }

    public function massDestroy(MassDestroyDesignerRequest $request)
    {
        $designers = Designer::find(request('ids'));

        foreach ($designers as $designer) {
            $designer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
