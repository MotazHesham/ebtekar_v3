<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPoliceRequest;
use App\Http\Requests\StorePoliceRequest;
use App\Http\Requests\UpdatePoliceRequest;
use App\Models\Police;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PolicesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('police_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $polices = Police::all();

        return view('admin.polices.index', compact('polices'));
    }

    public function create()
    {
        abort_if(Gate::denies('police_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.polices.create');
    }

    public function store(StorePoliceRequest $request)
    {
        $police = Police::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $police->id]);
        }

        return redirect()->route('admin.polices.index');
    }

    public function edit(Police $police)
    {
        abort_if(Gate::denies('police_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.polices.edit', compact('police'));
    }

    public function update(UpdatePoliceRequest $request, Police $police)
    {
        $police->update($request->all());

        return redirect()->route('admin.polices.index');
    }

    public function show(Police $police)
    {
        abort_if(Gate::denies('police_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.polices.show', compact('police'));
    }

    public function destroy(Police $police)
    {
        abort_if(Gate::denies('police_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $police->delete();

        return back();
    }

    public function massDestroy(MassDestroyPoliceRequest $request)
    {
        $polices = Police::find(request('ids'));

        foreach ($polices as $police) {
            $police->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('police_create') && Gate::denies('police_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Police();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
