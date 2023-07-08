<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroySellerRequest;
use App\Http\Requests\StoreSellerRequest;
use App\Http\Requests\UpdateSellerRequest;
use App\Models\Seller;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SellersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('seller_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Seller::with(['user'])->select(sprintf('%s.*', (new Seller)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'seller_show';
                $editGate      = 'seller_edit';
                $deleteGate    = 'seller_delete';
                $crudRoutePart = 'sellers';

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

            $table->editColumn('seller_type', function ($row) {
                return $row->seller_type ? Seller::SELLER_TYPE_SELECT[$row->seller_type] : '';
            });
            $table->editColumn('user_approved', function ($row) {
                return '
                <label class="c-switch c-switch-pill c-switch-success">
                    <input onchange="update_statuses(this,\'approved\')" value="' . $row->user->id . '" type="checkbox" class="c-switch-input" '. ($row->user->approved ? "checked" : null) .' }}>
                    <span class="c-switch-slider"></span>
                </label>';
            }); 
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('discount_code', function ($row) {
                return $row->discount_code ? $row->discount_code : '';
            });
            $table->editColumn('qualification', function ($row) {
                return $row->qualification ? $row->qualification : '';
            });
            $table->editColumn('social_name', function ($row) {
                return $row->social_name ? $row->social_name : '';
            });
            $table->editColumn('social_link', function ($row) {
                return $row->social_link ? $row->social_link : '';
            });
            $table->editColumn('seller_code', function ($row) {
                return $row->seller_code ? $row->seller_code : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user','user_approved']);

            return $table->make(true);
        }

        return view('admin.sellers.index');
    }

    public function create()
    {
        abort_if(Gate::denies('seller_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        return view('admin.sellers.create');
    }


    public function store(StoreSellerRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'user_type' => 'seller',
            'approved' => 1,
        ]);

        if ($request->input('photo', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        } 

        $random_string = generateRandomString();
        
        $seller = Seller::create([
            'user_id' => $user->id,
            'seller_type' => $request->seller_type,
            'seller_code' => $user->id . $random_string,
            'discount' => $request->discount,
            'discount_code' => $request->discount_code,
            'order_out_website' => $request->order_out_website,
            'qualification' => $request->qualification,
            'social_name' => $request->social_name,
            'social_link' => $request->social_link,
        ]);

        if ($request->input('identity_back', false)) {
            $seller->addMedia(storage_path('tmp/uploads/' . basename($request->input('identity_back'))))->toMediaCollection('identity_back');
        }

        if ($request->input('identity_front', false)) {
            $seller->addMedia(storage_path('tmp/uploads/' . basename($request->input('identity_front'))))->toMediaCollection('identity_front');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $seller->id]);
        }

        toast(trans('flash.global.success_title'),'success'); 
        return redirect()->route('admin.sellers.index');
    }

    public function edit(Seller $seller)
    {
        abort_if(Gate::denies('seller_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $seller->load('user');

        return view('admin.sellers.edit', compact('seller', 'users'));
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update([
            'seller_type' => $request->seller_type,
            'discount' => $request->discount,
            'discount_code' => $request->discount_code,
            'order_out_website' => $request->order_out_website,
            'qualification' => $request->qualification,
            'social_name' => $request->social_name,
            'social_link' => $request->social_link,
        ]);

        $user = User::find($seller->user_id);

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
        
        if ($request->input('identity_back', false)) {
            if (! $seller->identity_back || $request->input('identity_back') !== $seller->identity_back->file_name) {
                if ($seller->identity_back) {
                    $seller->identity_back->delete();
                }
                $seller->addMedia(storage_path('tmp/uploads/' . basename($request->input('identity_back'))))->toMediaCollection('identity_back');
            }
        } elseif ($seller->identity_back) {
            $seller->identity_back->delete();
        }

        if ($request->input('identity_front', false)) {
            if (! $seller->identity_front || $request->input('identity_front') !== $seller->identity_front->file_name) {
                if ($seller->identity_front) {
                    $seller->identity_front->delete();
                }
                $seller->addMedia(storage_path('tmp/uploads/' . basename($request->input('identity_front'))))->toMediaCollection('identity_front');
            }
        } elseif ($seller->identity_front) {
            $seller->identity_front->delete();
        }

        toast(trans('flash.global.update_title'),'success'); 
        return redirect()->route('admin.sellers.index');
    }

    public function show(Seller $seller)
    {
        abort_if(Gate::denies('seller_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seller->load('user');

        return view('admin.sellers.show', compact('seller'));
    }

    public function destroy(Seller $seller)
    {
        abort_if(Gate::denies('seller_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $seller->delete();

        alert(trans('flash.deleted'),'','success');
        return 1;
    }

    public function massDestroy(MassDestroySellerRequest $request)
    {
        $sellers = Seller::find(request('ids'));

        foreach ($sellers as $seller) {
            $seller->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('seller_create') && Gate::denies('seller_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Seller();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
