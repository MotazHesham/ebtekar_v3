<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptOutgoingsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptOutgoingRequest;
use App\Http\Requests\StoreReceiptOutgoingRequest;
use App\Http\Requests\UpdateReceiptOutgoingRequest;
use App\Models\GeneralSetting;
use App\Models\ReceiptOutgoing;
use App\Models\ReceiptOutgoingProduct;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptOutgoingController extends Controller
{
    public function print($id){
        $receipts = ReceiptOutgoing::with('receiptOutgoingReceiptOutgoingProducts','staff')->whereIn('id',[$id])->get(); 
        foreach($receipts as $receipt){
            $receipt->printing_times += 1;
            $receipt->save();
        }
        return view('admin.receiptOutgoings.print',compact('receipts'));
    }

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $receipt = ReceiptOutgoing::findOrFail($request->id);
        $receipt->$type = $request->status;
        $receipt->save();
        return 1;
    }

    public function duplicate($id){

        $receipt_outgoing = ReceiptOutgoing::findOrFail($id); 
        
        $new_receipt = new ReceiptOutgoing; 
        $new_receipt->client_name = $receipt_outgoing->client_name;
        $new_receipt->phone_number = $receipt_outgoing->phone_number;
        $new_receipt->total_cost = $receipt_outgoing->total_cost;
        $new_receipt->note = $receipt_outgoing->note;
        $new_receipt->save();

        $receipt_products = ReceiptOutgoingProduct::where('receipt_outgoing_id',$receipt_outgoing->id)->get();
        
        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_outgoing_id = $new_receipt->id;
            $new_receipt_product->save();
        }
        alert('Receipt has been inserted successfully','','success');
        return redirect()->route('admin.receipt-outgoings.index');
    }

    public function view_products(Request $request){
        if($request->ajax()){
            $receipt = ReceiptOutgoing::withTrashed()->find($request->id);
            $products = ReceiptOutgoingProduct::where('receipt_outgoing_id',$request->id)->latest()->get(); 
            return view('admin.receiptOutgoings.partials.view_products',compact('receipt','products'));
        }else{
            return '';
        }
    }

    public function destroy_product($id)
    {
        $receipt_outgoing_product = ReceiptOutgoingProduct::find($id);
        $receipt = ReceiptOutgoing::find($receipt_outgoing_product->receipt_outgoing_id); 

        $receipt_outgoing_product->delete();

        $receipt_outgoing_products = ReceiptOutgoingProduct::where('receipt_outgoing_id', $receipt->id)->get();
        $sum = 0;
        foreach ($receipt_outgoing_products as $row) {
            $sum += $row->total_cost; 
        }
        $receipt->total_cost = $sum; 
        $receipt->save();

        alert(trans('flash.deleted'),'','success'); 
        return 1;
    }
    public function edit_product(Request $request){
        if($request->ajax()){
            $receipt_outgoing_product = ReceiptOutgoingProduct::find($request->id); 
            return view('admin.receiptOutgoings.partials.edit_product',compact('receipt_outgoing_product'));
        }else{  
            $receipt_outgoing_product = ReceiptOutgoingProduct::find($request->receipt_outgoing_product_id);
            $receipt = ReceiptOutgoing::find($receipt_outgoing_product->receipt_outgoing_id);
            $receipt_outgoing_product->description = $request->description;
            $receipt_outgoing_product->price = $request->price;
            $receipt_outgoing_product->quantity = $request->quantity;
            $receipt_outgoing_product->total_cost = ($request->quantity * $request->price);
            $receipt_outgoing_product->save();

            // calculate the costing of products in receipt
            $all_receipt_outgoing_product = ReceiptOutgoingProduct::where('receipt_outgoing_id', $receipt->id)->get();
            $sum = 0;
            foreach ($all_receipt_outgoing_product as $row) {
                $sum += $row->total_cost;
            }

            // update the main receipt with new costing after calculation of its products
            $receipt->total_cost = $sum;
            $receipt->save(); 
            alert('Product has been Updated successfully','','success'); 
            return redirect()->route('admin.receipt-outgoings.index');
        }
    }

    public function add_product(Request $request){
        if($request->ajax()){
            $receipt_id = $request->id;
            return view('admin.receiptOutgoings.partials.add_product',compact('receipt_id'));
        }else{
            $receipt = ReceiptOutgoing::find($request->receipt_id);   
            $receipt_product_pivot = new ReceiptOutgoingProduct(); 
            $receipt_product_pivot->receipt_outgoing_id = $request->receipt_id;
            $receipt_product_pivot->description = $request->description;
            $receipt_product_pivot->price = $request->price;
            $receipt_product_pivot->quantity = $request->quantity; 
            $receipt_product_pivot->total_cost = ($request->quantity * $request->price);
            $receipt_product_pivot->save();
            
            $receipt_products = ReceiptOutgoingProduct::where('receipt_outgoing_id', $request->receipt_id)->get();
            $sum = 0;
            foreach ($receipt_products as $row) {
                $sum += $row->total_cost;
            }
            $receipt->total_cost = $sum;
            $receipt->save();

            alert(trans('flash.global.success_title'),trans('flash.global.success_body'),'success');
            return redirect()->route('admin.receipt-outgoings.index');
        }
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_outgoing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get();
        
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
        $deleted = null;

        if(request('deleted')){
            $deleted = 1; 
            $receipts = ReceiptOutgoing::with(['staff:id,name'])->onlyTrashed(); 
        }else{
            $receipts = ReceiptOutgoing::with(['staff:id,name']);  
        }

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }

        if ($request->staff_id != null) {
            $receipts = $receipts->where('staff_id', $request->staff_id);
            $staff_id = $request->staff_id;
        }

        if ($request->description != null) {
            $description = $request->description;
            $receipts = $receipts->whereHas('receiptOutgoingReceiptOutgoingProducts', function ($query) use ($description) {
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
            $receipts = $receipts->whereBetween('order_num', [ 'receipt-outgoing#' . $from,  'receipt-outgoing#' . $to]);
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
                $exclude2[] = 'receipt-outgoing#' . $exc;
            }
            $receipts = $receipts->whereNotIn('order_num', $exclude2);
        }
        if ($request->include != null) {
            $include = $request->include;
            foreach(explode(',',$include) as $inc){
                $include2[] = 'receipt-outgoing#' . $inc;
            }
            $receipts = $receipts->whereIn('order_num' ,$include2);
        }

        if ($request->has('print')) {
            $receipts = $receipts->with('receiptOutgoingReceiptOutgoingProducts')->get(); 
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptOutgoings.print', compact('receipts'));
        }
        
        if($request->has('download')){
            return Excel::download(new ReceiptOutgoingsExport($receipts->get()), 'outgoing_receipts_('.$from.')_('.$to.')_('. $request->client_name .').xlsx');
        }
        
        $statistics = [  
            'total_total_cost' => $receipts->sum('total_cost'),
        ];
        
        $receipts = $receipts->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.receiptOutgoings.index',compact(
            'staffs', 'phone', 'client_name', 'order_num', 'staff_id', 'from',
            'to', 'from_date', 'to_date', 'date_type', 'exclude', 'include',
            'done', 'description', 'receipts', 'statistics','deleted'));
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_outgoing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        return view('admin.receiptOutgoings.create');
    }

    public function store(StoreReceiptOutgoingRequest $request)
    {
        $receiptOutgoing = ReceiptOutgoing::create($request->all());

        return redirect()->route('admin.receipt-outgoings.index');
    }

    public function edit(ReceiptOutgoing $receiptOutgoing)
    {
        abort_if(Gate::denies('receipt_outgoing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $receiptOutgoing->load('staff');

        return view('admin.receiptOutgoings.edit', compact('receiptOutgoing'));
    }

    public function update(UpdateReceiptOutgoingRequest $request, ReceiptOutgoing $receiptOutgoing)
    {
        $receiptOutgoing->update($request->all());

        return redirect()->route('admin.receipt-outgoings.index');
    }

    public function show(ReceiptOutgoing $receiptOutgoing)
    {
        abort_if(Gate::denies('receipt_outgoing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoing->load('staff', 'receiptOutgoingReceiptOutgoingProducts');

        return view('admin.receiptOutgoings.show', compact('receiptOutgoing'));
    } 
    public function destroy($id)
    {
        abort_if(Gate::denies('receipt_outgoing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoing = ReceiptOutgoing::withTrashed()->find($id); 
        if($receiptOutgoing->deleted_at != null){
            $receiptOutgoing->forceDelete();
        }else{
            $receiptOutgoing->delete();
        }
        

        alert(trans('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_outgoing_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoing = ReceiptOutgoing::withTrashed()->find($id);
        $receiptOutgoing->restore();

        alert(trans('flash.restored'),'','success');

        return redirect()->route('admin.receipt-outgoings.index');
    } 
}
