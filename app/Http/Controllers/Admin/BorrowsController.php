<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBorrowRequest;
use App\Http\Requests\StoreBorrowRequest;
use App\Http\Requests\UpdateBorrowRequest;
use App\Models\Borrow;
use App\Models\Employee;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BorrowsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('borrow_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Borrow::with(['employee'])->select(sprintf('%s.*', (new Borrow)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'borrow_show';
                $editGate      = 'borrow_edit';
                $deleteGate    = 'borrow_delete';
                $crudRoutePart = 'borrows';

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
            $table->editColumn('status', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->status ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'placeholder', 'employee', 'status']);

            return $table->make(true);
        }

        return view('admin.borrows.index');
    }

    public function create()
    {
        abort_if(Gate::denies('borrow_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        return view('admin.borrows.create', compact('employees'));
    }

    public function store(StoreBorrowRequest $request)
    {
        $borrow = Borrow::create($request->all());

        return redirect()->route('admin.borrows.index');
    }

    public function edit(Borrow $borrow)
    {
        abort_if(Gate::denies('borrow_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $borrow->load('employee');

        return view('admin.borrows.edit', compact('borrow', 'employees'));
    }

    public function update(UpdateBorrowRequest $request, Borrow $borrow)
    {
        $borrow->update($request->all());

        return redirect()->route('admin.borrows.index');
    }

    public function show(Borrow $borrow)
    {
        abort_if(Gate::denies('borrow_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $borrow->load('employee');

        return view('admin.borrows.show', compact('borrow'));
    }

    public function destroy(Borrow $borrow)
    {
        abort_if(Gate::denies('borrow_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $borrow->delete();

        return back();
    }

    public function massDestroy(MassDestroyBorrowRequest $request)
    {
        $borrows = Borrow::find(request('ids'));

        foreach ($borrows as $borrow) {
            $borrow->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
