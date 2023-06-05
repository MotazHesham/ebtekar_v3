<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDesigneRequest;
use App\Http\Requests\StoreDesigneRequest;
use App\Http\Requests\UpdateDesigneRequest;
use App\Models\Designe;
use App\Models\Mockup;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DesignesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('designe_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Designe::with(['user', 'mockup'])->select(sprintf('%s.*', (new Designe)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'designe_show';
                $editGate      = 'designe_edit';
                $deleteGate    = 'designe_delete';
                $crudRoutePart = 'designes';

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
            $table->editColumn('design_name', function ($row) {
                return $row->design_name ? $row->design_name : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? $row->profit : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Designe::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('cancel_reason', function ($row) {
                return $row->cancel_reason ? $row->cancel_reason : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('mockup_name', function ($row) {
                return $row->mockup ? $row->mockup->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'mockup']);

            return $table->make(true);
        }

        return view('admin.designes.index');
    }

    public function create()
    {
        abort_if(Gate::denies('designe_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mockups = Mockup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.designes.create', compact('mockups', 'users'));
    }

    public function store(StoreDesigneRequest $request)
    {
        $designe = Designe::create($request->all());

        return redirect()->route('admin.designes.index');
    }

    public function edit(Designe $designe)
    {
        abort_if(Gate::denies('designe_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mockups = Mockup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $designe->load('user', 'mockup');

        return view('admin.designes.edit', compact('designe', 'mockups', 'users'));
    }

    public function update(UpdateDesigneRequest $request, Designe $designe)
    {
        $designe->update($request->all());

        return redirect()->route('admin.designes.index');
    }

    public function show(Designe $designe)
    {
        abort_if(Gate::denies('designe_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designe->load('user', 'mockup');

        return view('admin.designes.show', compact('designe'));
    }

    public function destroy(Designe $designe)
    {
        abort_if(Gate::denies('designe_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designe->delete();

        return back();
    }

    public function massDestroy(MassDestroyDesigneRequest $request)
    {
        $designes = Designe::find(request('ids'));

        foreach ($designes as $designe) {
            $designe->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
