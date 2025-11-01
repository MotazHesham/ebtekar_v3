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
use App\Models\RBranch;
use App\Models\RClient;
use App\Models\ReceiptBranch;
use App\Models\WebsiteSetting;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('expense_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Expense::with(['expense_category'])->select(sprintf('%s.*', (new Expense)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'expense_show';
                $editGate      = 'expense_edit';
                $deleteGate    = 'expense_delete';
                $crudRoutePart = 'expenses';

                // Only show actions if model_type is null (similar to original view logic)
                if ($row->model_type) {
                    return '';
                }

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
            $table->addColumn('expense_category_name', function ($row) {
                return $row->expense_category ? $row->expense_category->name : '';
            });
            $table->editColumn('entry_date', function ($row) {
                return $row->entry_date ? $row->entry_date : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'description']);

            return $table->make(true);
        }

        return view('admin.expenses.index');
    }

    public function create()
    {
        abort_if(Gate::denies('expense_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        return view('admin.expenses.create', compact('expense_categories'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $validated_request = $request->all(); 
        if($request->has('model_type')){
            
            if($request->model_type == 'App\Models\Employee'){
                $site_settings = WebsiteSetting::first();  
                if(Cookie::has('access_employee') && $site_settings->employee_password == Cookie::get('access_employee')){ 
                    // continue
                }else{
                    toast('قم بتسجيل الدخول مرة أخري لقائمة السلف','warning');
                    return redirect()->route('admin.home');
                }

                $employee = Employee::find($request->model_id);
                $month = substr($request->entry_date,3,2);
                $year = substr($request->entry_date,6,4); 

                if($request->has('download')){
                    return Excel::download(new EmployeeExport($employee,$month,$year), $employee->name.'_('.$month.')_('.$year.').xlsx');
                } 

                $validated_request['amount'] = $employee->calc_financials($month,$year); 
                $description = ' الراتب ' . $employee->salery . '<br>';
                
                foreach($employee->employeeEmployeeFinancials()->whereYear('entry_date', '=', $year)->whereMonth('entry_date', '=', $month)->get()->groupBy('financial_category_id') as $cat =>  $raw){
                    $description .=  FinancialCategory::find($cat)->name .' => ' . $raw->sum('amount') . '<br>';
                }

                $validated_request['description'] = $description;
        
                Expense::create($validated_request);

                return redirect()->route('admin.employees.show',$request->model_id);
            }elseif($request->model_type == 'App\Models\ReceiptBranch'){
                $expenses = Expense::where('model_type','App\Models\ReceiptBranch')->where('model_id',$request->model_id)->get();
                $total = $expenses ? $expenses->sum('amount') : 0;
                $receipt = ReceiptBranch::find($request->model_id);
                $receipt->permission_status = $total >= $receipt->calc_total_cost() ? 'permission_complete' : 'permission_segment';
                $receipt->save();
                Expense::create($validated_request);
                alert('تم صرف جزء من الأذن','','success');
                return redirect()->route('admin.receipt-branches.index');
            }elseif($request->model_type == 'App\Models\RBranch'){
                $rBranch = RBranch::find($request->model_id);
                $rBranch->remaining -= $request->amount;
                $rBranch->save();
                Expense::create($validated_request);
                alert('تم أضافة دفعة بنجاح','','success');
                return redirect()->route('admin.r-branches.show',$rBranch->id);
            }elseif($request->model_type == 'App\Models\RClient'){
                $rClient = RClient::find($request->model_id);
                $rClient->remaining -= $request->amount;
                $rClient->save();
                Expense::create($validated_request);
                alert('تم أضافة دفعة بنجاح','','success');
                return redirect()->route('admin.r-clients.show',$rClient->id);
            }
        }

        Expense::create($validated_request);

        return redirect()->route('admin.expenses.index');
    }

    public function edit(Expense $expense)
    {
        abort_if(Gate::denies('expense_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_categories = ExpenseCategory::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

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
