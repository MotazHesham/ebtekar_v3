<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySubscribeRequest;
use App\Models\Subscribe;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SubscribeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('subscribe_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Subscribe::query()->select(sprintf('%s.*', (new Subscribe)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'subscribe_show';
                $editGate      = 'subscribe_edit';
                $deleteGate    = 'subscribe_delete';
                $crudRoutePart = 'subscribes';

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
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.subscribes.index');
    }

    public function destroy(Subscribe $subscribe)
    {
        abort_if(Gate::denies('subscribe_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subscribe->delete();

        return back();
    }

    public function massDestroy(MassDestroySubscribeRequest $request)
    {
        $subscribes = Subscribe::find(request('ids'));

        foreach ($subscribes as $subscribe) {
            $subscribe->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
