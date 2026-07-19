<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySeasonRequest;
use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\UpdateSeasonRequest;
use App\Models\Season;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SeasonsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('season_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Season::query()->select(sprintf('%s.*', (new Season)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'season_show';
                $editGate      = 'season_edit';
                $deleteGate    = 'season_delete';
                $crudRoutePart = 'seasons';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', fn ($row) => $row->id ?? '');
            $table->editColumn('name', fn ($row) => $row->name ?? '');
            $table->editColumn('year', fn ($row) => $row->year ?? '');
            $table->editColumn('start_date', fn ($row) => $row->start_date ?? '');
            $table->editColumn('end_date', fn ($row) => $row->end_date ?? '');

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.seasons.index');
    }

    public function create()
    {
        abort_if(Gate::denies('season_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seasons.create');
    }

    public function store(StoreSeasonRequest $request)
    {
        Season::create($request->all());

        toast(__('flash.global.success_title'), 'success');

        return redirect()->route('admin.seasons.index');
    }

    public function edit(Season $season)
    {
        abort_if(Gate::denies('season_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seasons.edit', compact('season'));
    }

    public function update(UpdateSeasonRequest $request, Season $season)
    {
        $season->update($request->all());

        toast(__('flash.global.update_title'), 'success');

        return redirect()->route('admin.seasons.index');
    }

    public function show(Season $season)
    {
        abort_if(Gate::denies('season_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.seasons.show', compact('season'));
    }

    public function destroy(Season $season)
    {
        abort_if(Gate::denies('season_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $season->delete();

        alert(__('flash.deleted'), '', 'success');

        return 1;
    }

    public function massDestroy(MassDestroySeasonRequest $request)
    {
        $seasons = Season::find(request('ids'));

        foreach ($seasons as $season) {
            $season->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
