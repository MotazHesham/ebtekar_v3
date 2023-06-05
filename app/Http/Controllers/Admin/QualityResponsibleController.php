<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyQualityResponsibleRequest;
use App\Http\Requests\StoreQualityResponsibleRequest;
use App\Http\Requests\UpdateQualityResponsibleRequest;
use App\Models\QualityResponsible;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class QualityResponsibleController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('quality_responsible_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualityResponsibles = QualityResponsible::with(['media'])->get();

        return view('admin.qualityResponsibles.index', compact('qualityResponsibles'));
    }

    public function create()
    {
        abort_if(Gate::denies('quality_responsible_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualityResponsibles.create');
    }

    public function store(StoreQualityResponsibleRequest $request)
    {
        $qualityResponsible = QualityResponsible::create($request->all());

        if ($request->input('photo', false)) {
            $qualityResponsible->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $qualityResponsible->id]);
        }

        return redirect()->route('admin.quality-responsibles.index');
    }

    public function edit(QualityResponsible $qualityResponsible)
    {
        abort_if(Gate::denies('quality_responsible_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualityResponsibles.edit', compact('qualityResponsible'));
    }

    public function update(UpdateQualityResponsibleRequest $request, QualityResponsible $qualityResponsible)
    {
        $qualityResponsible->update($request->all());

        if ($request->input('photo', false)) {
            if (! $qualityResponsible->photo || $request->input('photo') !== $qualityResponsible->photo->file_name) {
                if ($qualityResponsible->photo) {
                    $qualityResponsible->photo->delete();
                }
                $qualityResponsible->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($qualityResponsible->photo) {
            $qualityResponsible->photo->delete();
        }

        return redirect()->route('admin.quality-responsibles.index');
    }

    public function show(QualityResponsible $qualityResponsible)
    {
        abort_if(Gate::denies('quality_responsible_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.qualityResponsibles.show', compact('qualityResponsible'));
    }

    public function destroy(QualityResponsible $qualityResponsible)
    {
        abort_if(Gate::denies('quality_responsible_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qualityResponsible->delete();

        return back();
    }

    public function massDestroy(MassDestroyQualityResponsibleRequest $request)
    {
        $qualityResponsibles = QualityResponsible::find(request('ids'));

        foreach ($qualityResponsibles as $qualityResponsible) {
            $qualityResponsible->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('quality_responsible_create') && Gate::denies('quality_responsible_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new QualityResponsible();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
