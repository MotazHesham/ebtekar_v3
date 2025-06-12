<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CountriesController extends Controller
{
    public function update_statuses(Request $request){ 
        $type = $request->type;
        $country = Country::findOrFail($request->id);
        $country->$type = $request->status; 
        $country->save();
        return 1;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('country_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
        $query = Country::query()->where('type','!=','cities')->select(sprintf('%s.*', (new Country)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'country_show';
                $editGate      = 'country_edit';
                $deleteGate    = 'country_delete';
                $crudRoutePart = 'countries';

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
            $table->editColumn('cost', function ($row) {
                return $row->cost ? $row->cost : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('code_cost', function ($row) {
                return $row->code_cost ? $row->code_cost : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? Country::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('status', function ($row) {
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'status\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->status ? "checked" : null) .'>
                    <span class="c-switch-slider"></span>
                </label>';
            });
            $table->editColumn('website', function ($row) {
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'website\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->website ? "checked" : null) .'>
                    <span class="c-switch-slider"></span>
                </label>';
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'website']);

            return $table->make(true);
        }

        return view('admin.countries.index');
    }

    public function create()
    {
        abort_if(Gate::denies('country_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.countries.create');
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->all());
        
        toast(__('flash.global.success_title'),'success');
        return redirect()->route('admin.countries.index');
    }

    public function edit(Country $country)
    {
        abort_if(Gate::denies('country_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.countries.edit', compact('country'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all()); 
        
        toast(__('flash.global.update_title'),'success');
        return redirect()->route('admin.countries.index');
    }

    public function show(Country $country)
    {
        abort_if(Gate::denies('country_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.countries.show', compact('country'));
    }

    public function destroy(Country $country)
    {
        abort_if(Gate::denies('country_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->delete();

        alert(__('flash.deleted'),'','success');

        return 1;
    }

    public function massDestroy(MassDestroyCountryRequest $request)
    {
        $countries = Country::find(request('ids'));

        foreach ($countries as $country) {
            $country->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
