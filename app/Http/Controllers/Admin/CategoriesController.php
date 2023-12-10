<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCategoryRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class CategoriesController extends Controller
{
    use MediaUploadingTrait;

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $category = Category::findOrFail($request->id);
        $category->$type = $request->status; 
        $category->save();
        Cache::forget('home_categories_'.$category->website_setting_id);
        Cache::forget('home_featured_categories_'.$category->website_setting_id);
        Cache::forget('header_nested_categories_'.$category->website_setting_id);
        return 1;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Category::query()->select(sprintf('%s.*', (new Category)->table))->with('website');
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'category_show';
                $editGate      = 'category_edit';
                $deleteGate    = 'category_delete';
                $crudRoutePart = 'categories';

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
            $table->editColumn('banner', function ($row) {
                if ($photo = $row->banner) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });
            $table->editColumn('icon', function ($row) {
                if ($photo = $row->icon) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
                        $photo->url,
                        $photo->thumbnail
                    );
                }

                return '';
            });  
            $table->editColumn('website_site_name', function ($row) { 
                return $row->website->site_name ?? '';
            });
            $table->editColumn('featured', function ($row) { 
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'featured\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->featured ? "checked" : null) .' }}>
                    <span class="c-switch-slider"></span>
                </label>';
            }); 
            $table->editColumn('published', function ($row) { 
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'published\')" value="' . $row->id . '" type="checkbox" class="c-switch-input" '. ($row->published ? "checked" : null) .' }}>
                    <span class="c-switch-slider"></span>
                </label>';
            }); 
            $table->rawColumns(['actions', 'placeholder', 'banner', 'icon', 'design', 'featured','published','website_site_name']);

            return $table->make(true);
        }

        return view('admin.categories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.categories.create',compact('websites'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated_request = $request->all();
        $validated_request['slug'] = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        $category = Category::create($validated_request);

        if ($request->input('banner', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
        }

        if ($request->input('icon', false)) {
            $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('icon'))))->toMediaCollection('icon');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $category->id]);
        }

        Cache::forget('header_nested_categories_'.$category->website_setting_id);
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.categories.index');
    }

    public function edit(Category $category)
    {
        abort_if(Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 
        
        return view('admin.categories.edit', compact('category','websites'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        if ($request->input('banner', false)) {
            if (! $category->banner || $request->input('banner') !== $category->banner->file_name) {
                if ($category->banner) {
                    $category->banner->delete();
                }
                $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner'))))->toMediaCollection('banner');
            }
        } elseif ($category->banner) {
            $category->banner->delete();
        }

        if ($request->input('icon', false)) {
            if (! $category->icon || $request->input('icon') !== $category->icon->file_name) {
                if ($category->icon) {
                    $category->icon->delete();
                }
                $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('icon'))))->toMediaCollection('icon');
            }
        } elseif ($category->icon) {
            $category->icon->delete();
        }

        Cache::forget('home_categories_'.$category->website_setting_id);
        Cache::forget('home_featured_categories_'.$category->website_setting_id);
        Cache::forget('header_nested_categories_'.$category->website_setting_id);
        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.categories.index');
    }

    public function show(Category $category)
    {
        abort_if(Gate::denies('category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.categories.show', compact('category'));
    }

    public function destroy(Category $category)
    {
        abort_if(Gate::denies('category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $category->delete();

        Cache::forget('home_categories_'.$category->website_setting_id);
        Cache::forget('home_featured_categories_'.$category->website_setting_id);
        Cache::forget('header_nested_categories_'.$category->website_setting_id);
        alert(trans('flash.deleted'),'','success');
        return 1;
    }

    public function massDestroy(MassDestroyCategoryRequest $request)
    {
        $categories = Category::find(request('ids'));

        foreach ($categories as $category) {
            $category->delete();
            Cache::forget('home_categories_'.$category->website_setting_id);
            Cache::forget('home_featured_categories_'.$category->website_setting_id);
            Cache::forget('header_nested_categories_'.$category->website_setting_id);
        } 
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('category_create') && Gate::denies('category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Category();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
