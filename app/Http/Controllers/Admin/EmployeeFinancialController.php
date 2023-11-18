<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyEmployeeFinancialRequest;
use App\Http\Requests\StoreEmployeeFinancialRequest;
use App\Http\Requests\UpdateEmployeeFinancialRequest;
use App\Models\Employee;
use App\Models\EmployeeFinancial;
use App\Models\FinancialCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmployeeFinancialController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('employee_financial_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = EmployeeFinancial::with(['employee', 'financial_category'])->select(sprintf('%s.*', (new EmployeeFinancial)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'employee_financial_show';
                $editGate      = 'employee_financial_edit';
                $deleteGate    = 'employee_financial_delete';
                $crudRoutePart = 'employee-financials';

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

            $table->addColumn('financial_category_name', function ($row) {
                return $row->financial_category ? $row->financial_category->name : '';
            });

            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'employee', 'financial_category']);

            return $table->make(true);
        }

        return view('admin.employeeFinancials.index');
    }

    public function create()
    {
        abort_if(Gate::denies('employee_financial_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $financial_categories = FinancialCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.employeeFinancials.create', compact('employees', 'financial_categories'));
    }

    public function store(StoreEmployeeFinancialRequest $request)
    {
        $financial_category = FinancialCategory::findOrFail($request->financial_category_id);
        $validated_request = $request->all();
        $validated_request['amount'] = $financial_category->type == 'minus' ? -$request->amount : $request->amount;

        EmployeeFinancial::create($validated_request);

        if($request->has('single_employee')){
            return redirect()->route('admin.employees.show',$request->employee_id);
        }
        return redirect()->route('admin.employee-financials.index');
    }

    public function edit(EmployeeFinancial $employeeFinancial)
    {
        abort_if(Gate::denies('employee_financial_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $financial_categories = FinancialCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employeeFinancial->load('employee', 'financial_category');

        return view('admin.employeeFinancials.edit', compact('employeeFinancial', 'employees', 'financial_categories'));
    }

    public function update(UpdateEmployeeFinancialRequest $request, EmployeeFinancial $employeeFinancial)
    {
        $employeeFinancial->update($request->all());

        return redirect()->route('admin.employee-financials.index');
    }

    public function show(EmployeeFinancial $employeeFinancial)
    {
        abort_if(Gate::denies('employee_financial_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employeeFinancial->load('employee', 'financial_category');

        return view('admin.employeeFinancials.show', compact('employeeFinancial'));
    }

    public function destroy(EmployeeFinancial $employeeFinancial)
    {
        abort_if(Gate::denies('employee_financial_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employeeFinancial->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmployeeFinancialRequest $request)
    {
        $employeeFinancials = EmployeeFinancial::find(request('ids'));

        foreach ($employeeFinancials as $employeeFinancial) {
            $employeeFinancial->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
