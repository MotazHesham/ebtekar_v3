<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySocialRequest;
use App\Http\Requests\StoreSocialRequest;
use App\Http\Requests\UpdateSocialRequest;
use App\Models\Social; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SocialsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('social_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $socials = Social::with(['media'])->get();

        return view('admin.socials.index', compact('socials'));
    }

    public function create()
    {
        abort_if(Gate::denies('social_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.socials.create');
    }

    public function store(StoreSocialRequest $request)
    {
        $social = Social::create($request->all());

        if ($request->input('photo', false)) {
            $social->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $social->id]);
        }

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.socials.index');
    }

    public function edit(Social $social)
    {
        abort_if(Gate::denies('social_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.socials.edit', compact('social'));
    }

    public function update(UpdateSocialRequest $request, Social $social)
    {
        $social->update($request->all());

        if ($request->input('photo', false)) {
            if (! $social->photo || $request->input('photo') !== $social->photo->file_name) {
                if ($social->photo) {
                    $social->photo->delete();
                }
                $social->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($social->photo) {
            $social->photo->delete();
        }

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.socials.index');
    }

    public function show(Social $social)
    {
        abort_if(Gate::denies('social_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.socials.show', compact('social'));
    }

    public function destroy(Social $social)
    {
        abort_if(Gate::denies('social_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $social->delete();

        alert(trans('flash.deleted'),'','success');

        return 1;
    }

    public function massDestroy(MassDestroySocialRequest $request)
    {
        $socials = Social::find(request('ids'));

        foreach ($socials as $social) {
            $social->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('social_create') && Gate::denies('social_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Social();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
