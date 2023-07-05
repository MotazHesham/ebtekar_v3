<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptPriceViewRequest;
use App\Http\Requests\StoreReceiptPriceViewRequest;
use App\Http\Requests\UpdateReceiptPriceViewRequest;
use App\Models\GeneralSetting;
use App\Models\ReceiptPriceView;
use App\Models\ReceiptPriceViewProduct;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptPriceViewController extends Controller
{
    public function print($id){
        $receipts = ReceiptPriceView::with('receiptPriceViewReceiptPriceViewProducts','staff')->whereIn('id',[$id])->get(); 
        foreach($receipts as $receipt){
            $receipt->printing_times += 1;
            $receipt->save();
        }
        return view('admin.receiptPriceViews.print',compact('receipts'));
    } 

    public function duplicate($id){

        $receipt_price_view = ReceiptPriceView::findOrFail($id); 
        
        $new_receipt = new ReceiptPriceView; 
        $new_receipt->client_name = $receipt_price_view->client_name;
        $new_receipt->phone_number = $receipt_price_view->phone_number;
        $new_receipt->save();

        $receipt_products = ReceiptPriceViewProduct::where('receipt_price_view_id',$receipt_price_view->id)->get();
        
        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_price_view_id = $new_receipt->id;
            $new_receipt_product->save();
        }
        alert('Receipt has been inserted successfully','','success');
        return redirect()->route('admin.receipt-price-views.index');
    }

    public function view_products(Request $request){
        if($request->ajax()){
            $receipt = ReceiptPriceView::withTrashed()->find($request->id);
            $products = ReceiptPriceViewProduct::where('receipt_price_view_id',$request->id)->latest()->get(); 
            return view('admin.receiptPriceViews.partials.view_products',compact('receipt','products'));
        }else{
            return '';
        }
    }

    public function destroy_product($id)
    {
        $receipt_price_view_product = ReceiptPriceViewProduct::find($id);
        $receipt = ReceiptPriceView::find($receipt_price_view_product->receipt_price_view_id); 

        $receipt_price_view_product->delete();

        $receipt_price_view_products = ReceiptPriceViewProduct::where('receipt_price_view_id', $receipt->id)->get();
        $sum = 0;
        foreach ($receipt_price_view_products as $row) {
            $sum += $row->total_cost; 
        }
        $receipt->total_cost = $sum; 
        $receipt->save();

        alert(trans('flash.deleted'),'','success'); 
        return 1;
    }
    public function edit_product(Request $request){
        if($request->ajax()){
            $receipt_price_view_product = ReceiptPriceViewProduct::find($request->id); 
            return view('admin.receiptPriceViews.partials.edit_product',compact('receipt_price_view_product'));
        }else{  
            $receipt_price_view_product = ReceiptPriceViewProduct::find($request->receipt_price_view_product_id);
            $receipt = ReceiptPriceView::find($receipt_price_view_product->receipt_price_view_id);
            $receipt_price_view_product->description = $request->description;
            $receipt_price_view_product->price = $request->price;
            $receipt_price_view_product->quantity = $request->quantity;
            $receipt_price_view_product->total_cost = ($request->quantity * $request->price);
            $receipt_price_view_product->save();

            // calculate the costing of products in receipt
            $all_receipt_price_view_product = ReceiptPriceViewProduct::where('receipt_price_view_id', $receipt->id)->get();
            $sum = 0;
            foreach ($all_receipt_price_view_product as $row) {
                $sum += $row->total_cost;
            }

            // update the main receipt with new costing after calculation of its products
            $receipt->total_cost = $sum;
            $receipt->save(); 
            alert('Product has been Updated successfully','','success'); 
            return redirect()->route('admin.receipt-price-views.index');
        }
    }

    public function add_product(Request $request){
        if($request->ajax()){
            $receipt_id = $request->id;
            return view('admin.receiptPriceViews.partials.add_product',compact('receipt_id'));
        }else{
            $receipt = ReceiptPriceView::find($request->receipt_id);   
            $receipt_product_pivot = new ReceiptPriceViewProduct(); 
            $receipt_product_pivot->receipt_price_view_id = $request->receipt_id;
            $receipt_product_pivot->description = $request->description;
            $receipt_product_pivot->price = $request->price;
            $receipt_product_pivot->quantity = $request->quantity; 
            $receipt_product_pivot->total_cost = ($request->quantity * $request->price);
            $receipt_product_pivot->save();
            
            $receipt_products = ReceiptPriceViewProduct::where('receipt_price_view_id', $request->receipt_id)->get();
            $sum = 0;
            foreach ($receipt_products as $row) {
                $sum += $row->total_cost;
            }
            $receipt->total_cost = $sum;
            $receipt->save();

            alert(trans('flash.global.success_title'),trans('flash.global.success_body'),'success');
            return redirect()->route('admin.receipt-price-views.index');
        }
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_price_view_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get();
        
        $websites = WebsiteSetting::pluck('site_name', 'id');

        $phone = null;
        $client_name = null;
        $order_num = null;
        $staff_id = null;
        $from = null;
        $to = null;
        $from_date = null;
        $to_date = null;
        $date_type = null;
        $exclude = null;
        $include = null;
        $done = null;
        $description = null;  
        $place = null;
        $deleted = null;
        $website_setting_id = null;

        if(request('deleted')){
            $deleted = 1; 
            $receipts = ReceiptPriceView::with(['staff:id,name'])->onlyTrashed(); 
        }else{
            $receipts = ReceiptPriceView::with(['staff:id,name']);  
        }

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }
        if ($request->place != null) {
            $place = $request->place;
            $receipts = $receipts->where('place', 'like', '%' . $place . '%');
        }

        if ($request->staff_id != null) {
            $receipts = $receipts->where('staff_id', $request->staff_id);
            $staff_id = $request->staff_id;
        }

        if ($request->website_setting_id != null) {
            $receipts = $receipts->where('website_setting_id', $request->website_setting_id);
            $website_setting_id = $request->website_setting_id;
        }

        if ($request->description != null) {
            $description = $request->description;
            $receipts = $receipts->whereHas('receiptPriceViewReceiptPriceViewProducts', function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }
        if ($request->phone != null) {
            global $phone;
            $phone = $request->phone;
            $receipts = $receipts->where('phone_number', 'like', '%' . $phone . '%');
        }

        if ($request->client_name != null) {
            $receipts = $receipts->where('client_name', 'like', '%' . $request->client_name . '%');
            $client_name = $request->client_name;
        }

        if ($request->order_num != null) {
            $receipts = $receipts->where('order_num', 'like', '%' . $request->order_num . '%');
            $order_num = $request->order_num;
        }

        if ($request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;
            $receipts = $receipts->whereBetween('order_num', [ 'receipt-price-view#' . $from,  'receipt-price-view#' . $to]);
        }
        if ($request->from_date != null && $request->to_date != null && $request->date_type != null) {  
            $from_date = \Carbon\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $request->from_date . ' ' . '12:00 am')->format('Y-m-d H:i:s');
            $to_date = \Carbon\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $request->to_date . ' ' . '11:59 pm')->format('Y-m-d H:i:s'); 
            $date_type = $request->date_type;
            $receipts = $receipts->whereBetween($date_type, [$from_date, $to_date]);
        }
        if ($request->exclude != null) {
            $exclude = $request->exclude; 
            foreach(explode(',',$exclude) as $exc){
                $exclude2[] = $exc;
            }
            $receipts = $receipts->where(function ($query) use($exclude2) {
                for ($i = 0; $i < count($exclude2); $i++){
                    $query->orwhere('order_num', 'not like',  '%' . $exclude2[$i] .'%');
                }      
            });
        }
        if ($request->include != null) {
            $include = $request->include; 
            foreach(explode(',',$include) as $inc){
                $include2[] = $inc;
            }
            $receipts = $receipts->where(function ($query) use($include2) {
                for ($i = 0; $i < count($include2); $i++){
                    $query->orwhere('order_num', 'like',  '%' . $include2[$i] .'%');
                }      
            });
        }

        if ($request->has('print')) {
            $receipts = $receipts->with('receiptPriceViewReceiptPriceViewProducts')->get(); 
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptPriceViews.print', compact('receipts'));
        }
        
        $statistics = [  
            'total_total_cost' => $receipts->sum('total_cost'),
        ];
        
        $receipts = $receipts->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.receiptPriceViews.index',compact(
            'staffs', 'phone', 'client_name', 'order_num', 'staff_id', 'from','website_setting_id',
            'to', 'from_date', 'to_date', 'date_type', 'exclude', 'include','websites',
            'done', 'description', 'receipts', 'statistics','deleted','place'));

    }

    public function create()
    {
        abort_if(Gate::denies('receipt_price_view_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        
        return view('admin.receiptPriceViews.create',compact('websites'));
    }

    public function store(StoreReceiptPriceViewRequest $request)
    {
        $receiptPriceView = ReceiptPriceView::create($request->all());

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-price-views.index');
    }

    public function edit(ReceiptPriceView $receiptPriceView)
    {
        abort_if(Gate::denies('receipt_price_view_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        return view('admin.receiptPriceViews.edit', compact('receiptPriceView','websites'));
    }

    public function update(UpdateReceiptPriceViewRequest $request, ReceiptPriceView $receiptPriceView)
    {
        $receiptPriceView->update($request->all());

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.receipt-price-views.index');
    }

    public function show(ReceiptPriceView $receiptPriceView)
    {
        abort_if(Gate::denies('receipt_price_view_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.receiptPriceViews.show', compact('receiptPriceView'));
    } 

    public function destroy($id)
    {
        abort_if(Gate::denies('receipt_price_view_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceView = ReceiptPriceView::withTrashed()->find($id); 
        if($receiptPriceView->deleted_at != null){
            $receiptPriceView->forceDelete();
        }else{
            $receiptPriceView->delete();
        }
        

        alert(trans('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_price_view_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceView = ReceiptPriceView::withTrashed()->find($id);
        $receiptPriceView->restore();

        alert(trans('flash.restored'),'','success');

        return redirect()->route('admin.receipt-price-views.index');
    } 
}
