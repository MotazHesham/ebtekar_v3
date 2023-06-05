<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMockupRequest;
use App\Http\Requests\StoreMockupRequest;
use App\Http\Requests\UpdateMockupRequest;
use App\Models\Category;
use App\Models\Mockup;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MockupsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('mockup_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Mockup::with(['category', 'sub_category', 'sub_sub_category'])->select(sprintf('%s.*', (new Mockup)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'mockup_show';
                $editGate      = 'mockup_edit';
                $deleteGate    = 'mockup_delete';
                $crudRoutePart = 'mockups';

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
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('preview_1', function ($row) {
                if ($photo = $row->preview_1) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('preview_2', function ($row) {
                if ($photo = $row->preview_2) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('preview_3', function ($row) {
                if ($photo = $row->preview_3) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('video_provider', function ($row) {
                return $row->video_provider ? $row->video_provider : '';
            });
            $table->editColumn('video_link', function ($row) {
                return $row->video_link ? $row->video_link : '';
            });
            $table->editColumn('purchase_price', function ($row) {
                return $row->purchase_price ? $row->purchase_price : '';
            });
            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });

            $table->addColumn('sub_category_name', function ($row) {
                return $row->sub_category ? $row->sub_category->name : '';
            });

            $table->addColumn('sub_sub_category_name', function ($row) {
                return $row->sub_sub_category ? $row->sub_sub_category->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'preview_1', 'preview_2', 'preview_3', 'category', 'sub_category', 'sub_sub_category']);

            return $table->make(true);
        }

        return view('admin.mockups.index');
    }

    public function create()
    {
        abort_if(Gate::denies('mockup_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sub_categories = SubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sub_sub_categories = SubSubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.mockups.create', compact('categories', 'sub_categories', 'sub_sub_categories'));
    }

    public function store(StoreMockupRequest $request)
    {
        $mockup = Mockup::create($request->all());

        if ($request->input('preview_1', false)) {
            $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_1'))))->toMediaCollection('preview_1');
        }

        if ($request->input('preview_2', false)) {
            $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_2'))))->toMediaCollection('preview_2');
        }

        if ($request->input('preview_3', false)) {
            $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_3'))))->toMediaCollection('preview_3');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $mockup->id]);
        }

        return redirect()->route('admin.mockups.index');
    }

    public function edit(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sub_categories = SubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sub_sub_categories = SubSubCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mockup->load('category', 'sub_category', 'sub_sub_category');

        return view('admin.mockups.edit', compact('categories', 'mockup', 'sub_categories', 'sub_sub_categories'));
    }

    public function update(UpdateMockupRequest $request, Mockup $mockup)
    {
        $mockup->update($request->all());

        if ($request->input('preview_1', false)) {
            if (! $mockup->preview_1 || $request->input('preview_1') !== $mockup->preview_1->file_name) {
                if ($mockup->preview_1) {
                    $mockup->preview_1->delete();
                }
                $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_1'))))->toMediaCollection('preview_1');
            }
        } elseif ($mockup->preview_1) {
            $mockup->preview_1->delete();
        }

        if ($request->input('preview_2', false)) {
            if (! $mockup->preview_2 || $request->input('preview_2') !== $mockup->preview_2->file_name) {
                if ($mockup->preview_2) {
                    $mockup->preview_2->delete();
                }
                $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_2'))))->toMediaCollection('preview_2');
            }
        } elseif ($mockup->preview_2) {
            $mockup->preview_2->delete();
        }

        if ($request->input('preview_3', false)) {
            if (! $mockup->preview_3 || $request->input('preview_3') !== $mockup->preview_3->file_name) {
                if ($mockup->preview_3) {
                    $mockup->preview_3->delete();
                }
                $mockup->addMedia(storage_path('tmp/uploads/' . basename($request->input('preview_3'))))->toMediaCollection('preview_3');
            }
        } elseif ($mockup->preview_3) {
            $mockup->preview_3->delete();
        }

        return redirect()->route('admin.mockups.index');
    }

    public function show(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mockup->load('category', 'sub_category', 'sub_sub_category');

        return view('admin.mockups.show', compact('mockup'));
    }

    public function destroy(Mockup $mockup)
    {
        abort_if(Gate::denies('mockup_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mockup->delete();

        return back();
    }

    public function massDestroy(MassDestroyMockupRequest $request)
    {
        $mockups = Mockup::find(request('ids'));

        foreach ($mockups as $mockup) {
            $mockup->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('mockup_create') && Gate::denies('mockup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Mockup();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
