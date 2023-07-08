<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Http\Requests\StoreDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Models\Category;
use App\Models\Design;
use App\Models\DesignImage;
use App\Models\Mockup;
use App\Models\MockupStock;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DesignsController extends Controller
{
    public function change_status($id,$status){
        $design = Design::findOrFail($id); 
        if($status == 'refused'){
            $design->status = 'refused';
            $design->save();
            alert('تم رفض التصميم','','success');
        }elseif($status == 'accepted'){ 
            $mockup = Mockup::findOrFail($design->mockup_id);
            $category = Category::find($mockup->category_id);
            $product = new Product();
            $product->name = $design->design_name;
            $product->design_id = $design->id;
            $product->added_by = 'designer';
            $product->user_id = $design->user_id;
            $product->category_id = $mockup->category_id;
            $product->sub_category_id = $mockup->sub_category_id;
            $product->sub_sub_category_id = $mockup->sub_sub_category_id;
            $product->website_setting_id = $category->website_setting_id ?? 1;
            $product->current_stock = 1000; 
            $product->description = $mockup->description;
            $product->video_provider = $mockup->video_provider;
            $product->video_link = $mockup->video_link;
            $product->unit_price = $mockup->purchase_price + $design->profit;
            $product->purchase_price = $mockup->purchase_price; 
            $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $design->design_name)).'-'.Str::random(7); 
            $product->colors = $design->colors;
            $product->attributes = $mockup->attributes;
            $product->attribute_options = $mockup->attribute_options;

            $design_images = DesignImage::where('design_id',$design->id)->get(); 

            foreach($design_images as $image){  
                $product->addMedia(storage_path('app/'.$image->image))->toMediaCollection('photos'); 
            } 

            $product->save(); 

            $mockup_stock = MockupStock::where('mockup_id',$mockup->id)->get();
            foreach($mockup_stock as $raw){
                $product_stock = new ProductStock();
                $product_stock->product_id = $product->id;
                $product_stock->variant = $raw->variant;
                $product_stock->unit_price = $raw->price + $mockup->profit;
                $product_stock->purchase_price = $raw->price;
                $product_stock->stock = $raw->quantity;
                $product_stock->save();
            }
            
            $product->save();

            $design->status = 'accepted';
            $design->save();
            alert('تم القبول والأضافة إلي منتجات الموقع','','success');
        }else{ 
            alert('حدث خطأ','','danger');
        }

        return redirect()->route('admin.designs.index');
    }

    public function design_images(Request $request){
        $design = Design::findOrFail($request->id);
        $design->load('design_images');
        return view('admin.designs.show',compact('design'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('design_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Design::with(['user', 'mockup'])->select(sprintf('%s.*', (new Design)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) { 
                $view      = '';
                $edit      = '';
                $delete    = '';  
                $view = '<a class="btn btn-xs btn-primary" href="#" onclick="design_images('.$row->id.')">
                            '. trans("global.view").'
                        </a>'; 
                if(Gate::allows('design_edit') && $row->status == 'pending'){
                    $edit  = '<a class="btn btn-xs btn-success" href="'.route('admin.designs.change_status', ['id' => $row->id,'status' => 'accepted' ]).'">
                                '. trans("global.accept").'
                            </a>';
                    $edit .= '<a class="btn btn-xs btn-warning" href="'.route('admin.designs.change_status', ['id' => $row->id,'status' => 'refused' ]).'">
                                '. trans("global.refused").'
                            </a>';
                } 
                if(Gate::allows('design_delete') && $row->status == 'refused'){ 
                    $delete  = '<a class="btn btn-xs btn-info" href="'.route('admin.designs.change_status', ['id' => $row->id,'status' => 'accepted']).'">
                                '. trans("global.accept").'
                            </a>';
                    $route = route('admin.designs.destroy', $row->id);
                    $delete .= '<a class="btn btn-xs btn-danger" href="#" onclick="deleteConfirmation('.$route.')">
                                '. trans("global.delete").'
                            </a>';
                } 
                return $view . $edit . $delete;
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('design_name', function ($row) {
                return $row->design_name ? $row->design_name : '';
            });
            $table->editColumn('profit', function ($row) {
                return $row->profit ? $row->profit : '';
            });
            $table->editColumn('profits', function ($row) {
                $calc = single_design_calcualtions($row);
                $pending = '<span class="badge badge-info">قيد الأنتظار <br>(x'.$calc['pending_quantity'].')'.$calc['pending'].'</span>';
                $available = '<span class="badge badge-success">متاح للتوريد <br>(x'.$calc['available_quantity'].')'.$calc['available'].'</span>';
                return $pending . $available;
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Design::STATUS_SELECT[$row->status] : '';
            }); 
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->addColumn('mockup_name', function ($row) {
                return $row->mockup ? $row->mockup->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user', 'mockup','profits']);

            return $table->make(true);
        }

        return view('admin.designs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('design_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mockups = Mockup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.designs.create', compact('mockups', 'users'));
    }

    public function store(StoreDesignRequest $request)
    {
        $design = Design::create($request->all());

        return redirect()->route('admin.designs.index');
    }

    public function edit(Design $design)
    {
        abort_if(Gate::denies('design_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mockups = Mockup::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $design->load('user', 'mockup');

        return view('admin.designs.edit', compact('design', 'mockups', 'users'));
    }

    public function update(UpdateDesignRequest $request, Design $design)
    {
        $design->update($request->all());

        return redirect()->route('admin.designs.index');
    }

    public function show(Design $design)
    {
        abort_if(Gate::denies('design_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $design->load('user', 'mockup');

        return view('admin.designs.show', compact('design'));
    }

    public function destroy(Design $design)
    {
        abort_if(Gate::denies('design_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $design->delete();

        return back();
    } 
}
