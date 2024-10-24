<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRClientRequest;
use App\Http\Requests\StoreRClientRequest;
use App\Http\Requests\UpdateRClientRequest;
use App\Models\RClient;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class RClientsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('r_client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type = request('type') ?? 'expense';

        if ($request->ajax()) {
            $query = RClient::query()->where('type',$type)->select(sprintf('%s.*', (new RClient)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'r_client_show';
                $editGate      = 'r_client_edit';
                $deleteGate    = 'r_client_delete';
                $crudRoutePart = 'r-clients';

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
            $table->editColumn('manage_type', function ($row) {
                return $row->manage_type ? RClient::MANAGE_TYPE_SELECT[$row->manage_type] : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.rClients.index',compact('type'));
    }

    public function create()
    {
        abort_if(Gate::denies('r_client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rClients.create');
    }

    public function store(StoreRClientRequest $request)
    {
        $rClient = RClient::create($request->all());

        return redirect()->route('admin.r-clients.index');
    }

    public function edit(RClient $rClient)
    {
        abort_if(Gate::denies('r_client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.rClients.edit', compact('rClient'));
    }

    public function update(UpdateRClientRequest $request, RClient $rClient)
    {
        $rClient->update($request->all());

        return redirect()->route('admin.r-clients.index');
    }

    public function show(RClient $rClient)
    {
        abort_if(Gate::denies('r_client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rClient->load('rClientRBranches');

        return view('admin.rClients.show', compact('rClient'));
    }

    public function destroy(RClient $rClient)
    {
        abort_if(Gate::denies('r_client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rClient->delete();

        return back();
    }

    public function massDestroy(MassDestroyRClientRequest $request)
    {
        $rClients = RClient::find(request('ids'));

        foreach ($rClients as $rClient) {
            $rClient->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
