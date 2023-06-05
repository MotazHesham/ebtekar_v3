<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommissionRequestRequest;
use App\Http\Requests\StoreCommissionRequestRequest;
use App\Http\Requests\UpdateCommissionRequestRequest;
use App\Models\CommissionRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CommissionRequestsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('commission_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = CommissionRequest::with(['user', 'created_by', 'done_by_user'])->select(sprintf('%s.*', (new CommissionRequest)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'commission_request_show';
                $editGate      = 'commission_request_edit';
                $deleteGate    = 'commission_request_delete';
                $crudRoutePart = 'commission-requests';

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
            $table->editColumn('status', function ($row) {
                return $row->status ? CommissionRequest::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('total_commission', function ($row) {
                return $row->total_commission ? $row->total_commission : '';
            });
            $table->editColumn('payment_method', function ($row) {
                return $row->payment_method ? CommissionRequest::PAYMENT_METHOD_SELECT[$row->payment_method] : '';
            });
            $table->editColumn('transfer_number', function ($row) {
                return $row->transfer_number ? $row->transfer_number : '';
            });

            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('created_by_name', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });

            $table->addColumn('done_by_user_name', function ($row) {
                return $row->done_by_user ? $row->done_by_user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'created_by', 'done_by_user']);

            return $table->make(true);
        }

        return view('admin.commissionRequests.index');
    }

    public function create()
    {
        abort_if(Gate::denies('commission_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $done_by_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.commissionRequests.create', compact('created_bies', 'done_by_users', 'users'));
    }

    public function store(StoreCommissionRequestRequest $request)
    {
        $commissionRequest = CommissionRequest::create($request->all());

        return redirect()->route('admin.commission-requests.index');
    }

    public function edit(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $done_by_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $commissionRequest->load('user', 'created_by', 'done_by_user');

        return view('admin.commissionRequests.edit', compact('commissionRequest', 'created_bies', 'done_by_users', 'users'));
    }

    public function update(UpdateCommissionRequestRequest $request, CommissionRequest $commissionRequest)
    {
        $commissionRequest->update($request->all());

        return redirect()->route('admin.commission-requests.index');
    }

    public function show(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commissionRequest->load('user', 'created_by', 'done_by_user');

        return view('admin.commissionRequests.show', compact('commissionRequest'));
    }

    public function destroy(CommissionRequest $commissionRequest)
    {
        abort_if(Gate::denies('commission_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $commissionRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyCommissionRequestRequest $request)
    {
        $commissionRequests = CommissionRequest::find(request('ids'));

        foreach ($commissionRequests as $commissionRequest) {
            $commissionRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
