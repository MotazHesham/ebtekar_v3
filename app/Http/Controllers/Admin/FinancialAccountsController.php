<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyFinancialAccountRequest;
use App\Http\Requests\StoreFinancialAccountRequest;
use App\Http\Requests\UpdateFinancialAccountRequest;
use App\Models\FinancialAccount;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FinancialAccountsController extends Controller
{
    public function update_statuses(Request $request){ 
        $type = $request->type;
        $raw = FinancialAccount::findOrFail($request->id);
        $raw->$type = $request->status; 
        $raw->save();
        return 1;
    }
    public function index()
    {
        abort_if(Gate::denies('financial_account_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $financialAccounts = FinancialAccount::all();

        return view('admin.financialAccounts.index', compact('financialAccounts'));
    }

    public function create()
    {
        abort_if(Gate::denies('financial_account_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialAccounts.create');
    }

    public function store(StoreFinancialAccountRequest $request)
    {
        $financialAccount = FinancialAccount::create($request->all());

        return redirect()->route('admin.financial-accounts.index');
    }

    public function edit(FinancialAccount $financialAccount)
    {
        abort_if(Gate::denies('financial_account_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialAccounts.edit', compact('financialAccount'));
    }

    public function update(UpdateFinancialAccountRequest $request, FinancialAccount $financialAccount)
    {
        $financialAccount->update($request->all());

        return redirect()->route('admin.financial-accounts.index');
    }

    public function show(FinancialAccount $financialAccount)
    {
        abort_if(Gate::denies('financial_account_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.financialAccounts.show', compact('financialAccount'));
    }

    public function destroy(FinancialAccount $financialAccount)
    {
        abort_if(Gate::denies('financial_account_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $financialAccount->delete();

        return back();
    }

    public function massDestroy(MassDestroyFinancialAccountRequest $request)
    {
        $financialAccounts = FinancialAccount::find(request('ids'));

        foreach ($financialAccounts as $financialAccount) {
            $financialAccount->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
