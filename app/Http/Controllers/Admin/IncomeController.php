<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyIncomeRequest;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\FinancialAccount;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\RBranch;
use App\Models\RClient;
use App\Models\ReceiptBranch;
use App\Models\ReceiptClient;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncomeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('income_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $incomes = Income::with(['income_category'])->orderby('created_at','desc')->paginate(25);

        return view('admin.incomes.index', compact('incomes'));
    }

    public function create()
    {
        abort_if(Gate::denies('income_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $income_categories = IncomeCategory::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        return view('admin.incomes.create', compact('income_categories'));
    }

    public function store(StoreIncomeRequest $request)
    {
        $income = Income::create($request->all());

        if($request->has('model_type')){
            if($request->model_type == 'App\Models\ReceiptBranch'){
                $incomes = Income::where('model_type','App\Models\ReceiptBranch')->where('model_id',$request->model_id)->get();
                $total = $incomes ? $incomes->sum('amount') : 0;
                $receipt = ReceiptBranch::find($request->model_id);
                $receipt->permission_status = $total >= $receipt->calc_total_cost() ? 'permission_complete' : 'permission_segment';
                $receipt->save();
                alert('تم صرف جزء من الأذن','','success');
                return redirect()->route('admin.receipt-branches.index');
            }elseif($request->model_type == 'App\Models\RBranch'){
                $rBranch = RBranch::find($request->model_id);
                $rBranch->remaining -= $request->amount;
                $rBranch->save();
                alert('تم أضافة دفعة بنجاح','','success');
                return redirect()->route('admin.r-branches.show',$rBranch->id);
            }elseif($request->model_type == 'App\Models\RClient'){
                $rClient = RClient::find($request->model_id);
                $rClient->remaining -= $request->amount;
                $rClient->save();
                alert('تم أضافة دفعة بنجاح','','success');
                return redirect()->route('admin.r-clients.show',$rClient->id);
            }elseif($request->model_type == 'App\Models\FinancialAccount'){ 
                alert('تم أضافة سحب بنجاح','','success');
                return redirect()->route('admin.financial-accounts.show',$request->model_id);
            }
        }
        return redirect()->route('admin.incomes.index');
    }

    public function edit(Income $income)
    {
        abort_if(Gate::denies('income_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $income_categories = IncomeCategory::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $income->load('income_category');

        
        if($income->model_type){
            alert("Cant Update This income",'','error');
            return redirect()->back();
        }

        return view('admin.incomes.edit', compact('income', 'income_categories'));
    }

    public function update(UpdateIncomeRequest $request, Income $income)
    {
        if($income->model_type){
            alert("Cant Update This income",'','error');
            return redirect()->back();
        }
        $income->update($request->all());

        return redirect()->route('admin.incomes.index');
    }

    public function show(Income $income)
    {
        abort_if(Gate::denies('income_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $income->load('income_category');

        return view('admin.incomes.show', compact('income'));
    }

    public function destroy(Income $income)
    {
        abort_if(Gate::denies('income_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $income->delete();

        return back();
    }

    public function massDestroy(MassDestroyIncomeRequest $request)
    {
        $incomes = Income::find(request('ids'));

        foreach ($incomes as $income) {
            $income->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
