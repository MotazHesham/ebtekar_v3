<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\Country; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ZoneController extends Controller
{
    
    public function index()
    {
        abort_if(Gate::denies('country_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zones = Zone::with(['countries'])->get();

        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        abort_if(Gate::denies('country_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(__('global.pleaseSelect'), ''); 

        return view('admin.zones.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $zone = Zone::create($request->all());
        $zone->countries()->sync($request->input('countries', [])); 

        return redirect()->route('admin.zones.index');
    }

    public function edit(Zone $zone)
    {
        abort_if(Gate::denies('country_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $countries = Country::pluck('name', 'id')->prepend(__('global.pleaseSelect'), '');

        $zone->load('countries');

        return view('admin.zones.edit', compact('countries', 'zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $zone->update($request->all());
        $zone->countries()->sync(ids: $request->input('countries', []));

        foreach($zone->countries as $country){
            $country->update([
                'cost' => $request->delivery_cost
            ]);
        }

        return redirect()->route('admin.zones.index');
    } 
    public function destroy(Zone $zone)
    {
        abort_if(Gate::denies('country_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone->delete();

        return back();
    } 
}
