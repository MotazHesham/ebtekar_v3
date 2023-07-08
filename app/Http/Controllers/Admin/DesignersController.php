<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDesignerRequest;
use App\Http\Requests\StoreDesignerRequest;
use App\Http\Requests\UpdateDesignerRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Designer;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class DesignersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('designer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Designer::query()->with('user')->select(sprintf('%s.*', (new Designer)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'designer_show';
                $editGate      = 'designer_edit';
                $deleteGate    = 'designer_delete';
                $crudRoutePart = 'designers';

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
            $table->editColumn('user', function ($row) {
                return $row->user ? $row->user : '';
            });
            $table->editColumn('store_name', function ($row) {
                return $row->store_name ? $row->store_name : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });
            $table->addColumn('user_email', function ($row) {
                return $row->user ? $row->user->email : '';
            });
            $table->addColumn('user_address', function ($row) {
                return $row->user ? $row->user->address : '';
            });
            $table->addColumn('user_phone_number', function ($row) {
                return $row->user ? $row->user->phone_number : '';
            });
            $table->editColumn('user_approved', function ($row) {
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'approved\')" value="' . $row->user->id . '" type="checkbox" class="c-switch-input" '. ($row->user->approved ? "checked" : null) .' }}>
                    <span class="c-switch-slider"></span>
                </label>';
            }); 

            $table->rawColumns(['actions', 'placeholder', 'user','user_approved']);

            return $table->make(true);
        }

        return view('admin.designers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('designer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.create');
    }

    public function store(StoreDesignerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'user_type' => 'designer',
            'approved' => 1,
        ]); 
        if ($request->input('photo', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        } 
        Designer::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name, 
        ]);

        toast(trans('flash.global.success_title'),'success'); 
        return redirect()->route('admin.designers.index');
    }

    public function edit(Designer $designer)
    {
        abort_if(Gate::denies('designer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.edit', compact('designer'));
    }

    public function update(UpdateDesignerRequest $request, Designer $designer)
    {
        $designer->update([ 
            'store_name' => $request->store_name, 
        ]);


        $user = User::find($designer->user_id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        if ($request->input('photo', false)) {
            if (! $user->photo || $request->input('photo') !== $user->photo->file_name) {
                if ($user->photo) {
                    $user->photo->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($user->photo) {
            $user->photo->delete();
        }
        toast(trans('flash.global.update_title'),'success'); 
        return redirect()->route('admin.designers.index');
    }

    public function show(Designer $designer)
    {
        abort_if(Gate::denies('designer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.designers.show', compact('designer'));
    }

    public function destroy(Designer $designer)
    {
        abort_if(Gate::denies('designer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $designer->delete();

        alert(trans('flash.deleted'),'','success');
        return 1;
    }

    public function massDestroy(MassDestroyDesignerRequest $request)
    {
        $designers = Designer::find(request('ids'));

        foreach ($designers as $designer) {
            $designer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('seller_create') && Gate::denies('seller_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Designer();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
