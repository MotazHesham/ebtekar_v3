<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EmployeeExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExpenseRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\FinancialCategory;
use Gate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExpenseController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenses = Expense::with(['expense_category'])->get();

        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.expenses.create', compact('expense_categories'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $validated_request = $request->all(); 
        if($request->has('model_type')){
            
            if($request->model_type == 'App\Models\Employee'){
                $employee = Employee::find($request->model_id);
                $month = substr($request->entry_date,3,2);
                $year = substr($request->entry_date,6,4); 

                if($request->has('download')){
                    return Excel::download(new EmployeeExport($employee,$month,$year), $employee->name.'_('.$month.')_('.$year.').xlsx');
                } 

                $validated_request['amount'] = $employee->calc_financials($month,$year); 
                $description = ' الراتب ' . $employee->salery . '<br>';
                
                foreach($employee->employeeEmployeeFinancials()->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->get()->groupBy('financial_category_id') as $cat =>  $raw){
                    $description .=  FinancialCategory::find($cat)->name .' => ' . $raw->sum('amount') . '<br>';
                }

                $validated_request['description'] = $description;
        
                Expense::create($validated_request);

                return redirect()->route('admin.employees.show',$request->model_id);
            }
        }

        Expense::create($validated_request);

        return redirect()->route('admin.expenses.index');
    }

    public function edit(Expense $expense)
    {
        abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $expense->load('expense_category');

        if($expense->model_type){
            alert("Cant Update This expense",'','error');
            return redirect()->back();
        }
        return view('admin.expenses.edit', compact('expense', 'expense_categories'));
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        if($expense->model_type){
            alert("Cant Update This expense",'','error');
            return redirect()->back();
        }
        $expense->update($request->all());

        return redirect()->route('admin.expenses.index');
    }

    public function show(Expense $expense)
    {
        abort_if(Gate::denies('expense_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense->load('expense_category');

        return view('admin.expenses.show', compact('expense'));
    }

    public function destroy(Expense $expense)
    {
        abort_if(Gate::denies('expense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense->delete();

        return back();
    }

    public function massDestroy(MassDestroyExpenseRequest $request)
    {
        $expenses = Expense::find(request('ids'));

        foreach ($expenses as $expense) {
            $expense->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
