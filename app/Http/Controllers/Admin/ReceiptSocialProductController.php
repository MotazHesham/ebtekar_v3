<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptProductExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReceiptSocialProductRequest;
use App\Http\Requests\StoreReceiptSocialProductRequest;
use App\Http\Requests\UpdateReceiptSocialProductRequest;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\WebsiteSetting;
use Gate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ReceiptSocialProductController extends Controller
{
    use MediaUploadingTrait;

    public function products_report(Request $request){ 
        $website_setting_id = $request->website_setting_id; 
        $products = ReceiptSocialProductPivot::whereHas('receipt',function($q) use($website_setting_id){
            if($website_setting_id){
                $q->where('website_setting_id',$website_setting_id);
            }
            return $q->where('confirm',1);
        });
        
        if($request->product_type != null){
            if($request->product_type == 1){
                $products = $products->whereHas('products',function($q){
                    $q->where('product_type','season');
                });
                
            }elseif($request->product_type == 0){
                $products = $products->whereHas('products',function($q){
                    $q->whereNull('product_type');
                });
            }
        }

        $products = $products->whereBetween(
            'created_at',
            [
                Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $request->start_date . ' 00:00:00')->format('Y-m-d H:i:s'),
                Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $request->end_date . ' 23:59:59')->format('Y-m-d H:i:s')
            ]
        )->selectRaw('sum(quantity) as quantity,sum(total_cost) as total_cost,receipt_social_product_id,title')->groupBy('receipt_social_product_id')->get();

        return view('admin.receiptSocials.partials.products-report',compact('products'));
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_social_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(!$request->ajax() && $request->has('download')){ 

            $products = ReceiptSocialProduct::orderBy('created_at','desc')->with('receiptProducts.receipt');

            if ($request->search != null){ 
                $GLOBALS['search'] = $request->search;
                $products = $products->where(function($Q){
                    $Q->where('name','like','%'.$GLOBALS['search'].'%')->orWhere('price','like','%'.$GLOBALS['search'].'%');
                })->orderBy('created_at','desc');
            }
            
            if ($request->website_setting_id != null){  
                $products = $products->where('website_setting_id',$request->website_setting_id);
            }

            if ($request->from_date != null && $request->to_date != null) { 
                $GLOBALS['from_date'] = Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $request->from_date . ' ' . '00:00:00')->format('Y-m-d H:i:s');
                $GLOBALS['to_date'] = Carbon::createFromFormat(config('panel.date_format') . ' H:i:s', $request->to_date . ' ' . '23:59:59')->format('Y-m-d H:i:s');  
                $products = $products->with('receiptProducts',function($q){
                    return $q->whereBetween('created_at',[$GLOBALS['from_date'] ,$GLOBALS['to_date']]);
                }); 
            }  
            return Excel::download(new ReceiptProductExport($products->get()), 'receipt_products.xlsx'); 
        }

        if ($request->ajax()) {
            $query = ReceiptSocialProduct::with('website')->select(sprintf('%s.*', (new ReceiptSocialProduct)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_social_product_show';
                $editGate      = 'receipt_social_product_edit';
                $deleteGate    = 'receipt_social_product_delete';
                $crudRoutePart = 'receipt-social-products';

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
                $name = $row->name ? $row->name : '';
                if($row->shopify_id){
                    $name .= ' <span class="badge rounded-pill text-bg-success text-white">
                    Shopify id #'.$row->shopify_id.'</span>';
                }
                return $name;
            });
            $table->editColumn('product_type', function ($row) {
                return $row->product_type ? ReceiptSocialProduct::PRODUCT_TYPE_SELECT[$row->product_type] : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? $row->commission : '';
            });
            $table->editColumn('photos', function ($row) {
                if (! $row->photos) {
                    return '';
                }
                $links = [];
                foreach ($row->photos as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });

            $table->editColumn('website_site_name', function ($row) { 
                return $row->website->site_name ?? '';
            });

            $table->rawColumns(['actions', 'placeholder', 'photos','website_site_name','name']);

            return $table->make(true);
        }

        $enable_multiple_form_submit = true;
        
        return view('admin.receiptSocialProducts.index',compact('enable_multiple_form_submit'));
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_social_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id');
        return view('admin.receiptSocialProducts.create',compact('websites'));
    }

    public function store(StoreReceiptSocialProductRequest $request)
    {
        $receiptSocialProduct = ReceiptSocialProduct::create($request->all());

        foreach ($request->input('photos', []) as $file) {
            $receiptSocialProduct->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $receiptSocialProduct->id]);
        }

        return redirect()->route('admin.receipt-social-products.index');
    }

    public function edit(ReceiptSocialProduct $receiptSocialProduct)
    {
        abort_if(Gate::denies('receipt_social_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocialProduct->load('receipts');

        $websites = WebsiteSetting::pluck('site_name', 'id');
        return view('admin.receiptSocialProducts.edit', compact('receiptSocialProduct','websites'));
    }

    public function update(UpdateReceiptSocialProductRequest $request, ReceiptSocialProduct $receiptSocialProduct)
    {
        $receiptSocialProduct->update($request->all());

        if (count($receiptSocialProduct->photos) > 0) {
            foreach ($receiptSocialProduct->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $receiptSocialProduct->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $receiptSocialProduct->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.receipt-social-products.index');
    }

    public function show(ReceiptSocialProduct $receiptSocialProduct)
    {
        abort_if(Gate::denies('receipt_social_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocialProduct->load('receipts');

        return view('admin.receiptSocialProducts.show', compact('receiptSocialProduct'));
    }

    public function destroy(ReceiptSocialProduct $receiptSocialProduct)
    {
        abort_if(Gate::denies('receipt_social_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocialProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptSocialProductRequest $request)
    {
        $receiptSocialProducts = ReceiptSocialProduct::find(request('ids'));

        foreach ($receiptSocialProducts as $receiptSocialProduct) {
            $receiptSocialProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('receipt_social_product_create') && Gate::denies('receipt_social_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ReceiptSocialProduct();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
