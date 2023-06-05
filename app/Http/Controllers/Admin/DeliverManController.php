<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDeliverManRequest;
use App\Http\Requests\StoreDeliverManRequest;
use App\Http\Requests\UpdateDeliverManRequest;
use App\Models\DeliverMan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DeliverManController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('deliver_man_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DeliverMan::query()->select(sprintf('%s.*', (new DeliverMan)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'deliver_man_show';
                $editGate      = 'deliver_man_edit';
                $deleteGate    = 'deliver_man_delete';
                $crudRoutePart = 'deliver-men';

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
            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.deliverMen.index');
    }

    public function create()
    {
        abort_if(Gate::denies('deliver_man_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliverMen.create');
    }

    public function store(StoreDeliverManRequest $request)
    {
        $deliverMan = DeliverMan::create($request->all());

        return redirect()->route('admin.deliver-men.index');
    }

    public function edit(DeliverMan $deliverMan)
    {
        abort_if(Gate::denies('deliver_man_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliverMen.edit', compact('deliverMan'));
    }

    public function update(UpdateDeliverManRequest $request, DeliverMan $deliverMan)
    {
        $deliverMan->update($request->all());

        return redirect()->route('admin.deliver-men.index');
    }

    public function show(DeliverMan $deliverMan)
    {
        abort_if(Gate::denies('deliver_man_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.deliverMen.show', compact('deliverMan'));
    }

    public function destroy(DeliverMan $deliverMan)
    {
        abort_if(Gate::denies('deliver_man_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliverMan->delete();

        return back();
    }

    public function massDestroy(MassDestroyDeliverManRequest $request)
    {
        $deliverMen = DeliverMan::find(request('ids'));

        foreach ($deliverMen as $deliverMan) {
            $deliverMan->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
