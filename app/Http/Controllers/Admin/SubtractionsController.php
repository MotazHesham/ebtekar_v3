<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubtractionRequest;
use App\Http\Requests\StoreSubtractionRequest;
use App\Http\Requests\UpdateSubtractionRequest;
use App\Models\Employee;
use App\Models\Subtraction;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubtractionsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subtraction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subtraction::with(['employee'])->select(sprintf('%s.*', (new Subtraction)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'subtraction_show';
                $editGate      = 'subtraction_edit';
                $deleteGate    = 'subtraction_delete';
                $crudRoutePart = 'subtractions';

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
            $table->addColumn('employee_name', function ($row) {
                return $row->employee ? $row->employee->name : '';
            });

            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });
            $table->editColumn('status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->status ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'employee', 'status']);

            return $table->make(true);
        }

        return view('admin.subtractions.index');
    }

    public function create()
    {
        abort_if(Gate::denies('subtraction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        return view('admin.subtractions.create', compact('employees'));
    }

    public function store(StoreSubtractionRequest $request)
    {
        $subtraction = Subtraction::create($request->all());

        return redirect()->route('admin.subtractions.index');
    }

    public function edit(Subtraction $subtraction)
    {
        abort_if(Gate::denies('subtraction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $subtraction->load('employee');

        return view('admin.subtractions.edit', compact('employees', 'subtraction'));
    }

    public function update(UpdateSubtractionRequest $request, Subtraction $subtraction)
    {
        $subtraction->update($request->all());

        return redirect()->route('admin.subtractions.index');
    }

    public function show(Subtraction $subtraction)
    {
        abort_if(Gate::denies('subtraction_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subtraction->load('employee');

        return view('admin.subtractions.show', compact('subtraction'));
    }

    public function destroy(Subtraction $subtraction)
    {
        abort_if(Gate::denies('subtraction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subtraction->delete();

        return back();
    }

    public function massDestroy(MassDestroySubtractionRequest $request)
    {
        $subtractions = Subtraction::find(request('ids'));

        foreach ($subtractions as $subtraction) {
            $subtraction->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
