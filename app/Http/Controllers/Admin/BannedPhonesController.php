<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBannedPhoneRequest;
use App\Http\Requests\StoreBannedPhoneRequest;
use App\Http\Requests\UpdateBannedPhoneRequest;
use App\Models\BannedPhone;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BannedPhonesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('banned_phone_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BannedPhone::query()->select(sprintf('%s.*', (new BannedPhone)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'banned_phone_show';
                $editGate      = 'banned_phone_edit';
                $deleteGate    = 'banned_phone_delete';
                $crudRoutePart = 'banned-phones';

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
            $table->editColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
            });
            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.bannedPhones.index');
    }

    public function create()
    {
        abort_if(Gate::denies('banned_phone_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bannedPhones.create');
    }

    public function store(StoreBannedPhoneRequest $request)
    {
        $bannedPhone = BannedPhone::create($request->all());

        return redirect()->route('admin.banned-phones.index');
    }

    public function edit(BannedPhone $bannedPhone)
    {
        abort_if(Gate::denies('banned_phone_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bannedPhones.edit', compact('bannedPhone'));
    }

    public function update(UpdateBannedPhoneRequest $request, BannedPhone $bannedPhone)
    {
        $bannedPhone->update($request->all());

        return redirect()->route('admin.banned-phones.index');
    }

    public function show(BannedPhone $bannedPhone)
    {
        abort_if(Gate::denies('banned_phone_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bannedPhones.show', compact('bannedPhone'));
    }

    public function destroy(BannedPhone $bannedPhone)
    {
        abort_if(Gate::denies('banned_phone_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bannedPhone->delete();

        return back();
    }

    public function massDestroy(MassDestroyBannedPhoneRequest $request)
    {
        $bannedPhones = BannedPhone::find(request('ids'));

        foreach ($bannedPhones as $bannedPhone) {
            $bannedPhone->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
