<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRBranchRequest;
use App\Http\Requests\StoreRBranchRequest;
use App\Http\Requests\UpdateRBranchRequest;
use App\Models\RBranch;
use App\Models\RClient;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RBrancheController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('r_branch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = RBranch::with(['r_client'])->select(sprintf('%s.*', (new RBranch)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'r_branch_show';
                $editGate      = 'r_branch_edit';
                $deleteGate    = 'r_branch_delete';
                $crudRoutePart = 'r-branches';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('phone_number', function ($row) {
                return $row->phone_number ? $row->phone_number : '';
            });
            $table->editColumn('payment_type', function ($row) {
                return $row->payment_type ? RBranch::PAYMENT_TYPE_SELECT[$row->payment_type] : '';
            });
            $table->addColumn('r_client_name', function ($row) {
                return $row->r_client ? $row->r_client->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'r_client']);

            return $table->make(true);
        }

        return view('admin.rBranches.index');
    }

    public function create()
    {
        abort_if(Gate::denies('r_branch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $r_clients = RClient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.rBranches.create', compact('r_clients'));
    }

    public function store(StoreRBranchRequest $request)
    {
        $rBranch = RBranch::create($request->all());

        return redirect()->route('admin.r-branches.index');
    }

    public function edit(RBranch $rBranch)
    {
        abort_if(Gate::denies('r_branch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $r_clients = RClient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rBranch->load('r_client');

        return view('admin.rBranches.edit', compact('rBranch', 'r_clients'));
    }

    public function update(UpdateRBranchRequest $request, RBranch $rBranch)
    {
        $rBranch->update($request->all());

        return redirect()->route('admin.r-branches.index');
    }

    public function show(RBranch $rBranch)
    {
        abort_if(Gate::denies('r_branch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rBranch->load('r_client');

        return view('admin.rBranches.show', compact('rBranch'));
    }

    public function destroy(RBranch $rBranch)
    {
        abort_if(Gate::denies('r_branch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rBranch->delete();

        return back();
    }

    public function massDestroy(MassDestroyRBranchRequest $request)
    {
        $rBranches = RBranch::find(request('ids'));

        foreach ($rBranches as $rBranch) {
            $rBranch->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
