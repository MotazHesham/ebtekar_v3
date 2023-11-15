<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptClientExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptClientRequest;
use App\Http\Requests\StoreReceiptClientRequest;
use App\Http\Requests\UpdateReceiptClientRequest;
use App\Models\GeneralSetting;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\RBranch;
use App\Models\RClient;
use App\Models\ReceiptClient;
use App\Models\ReceiptClientProduct;
use App\Models\ReceiptClientProductPivot;
use App\Models\User;
use App\Models\WebsiteSetting;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptClientController extends Controller
{
    
    public function receive_money($id){
        $receipt = ReceiptClient::find($id); 
        return view('partials.receive_money',compact('receipt'));
    }

    public function print($id){
        $receipts = ReceiptClient::with('receiptsReceiptClientProducts','staff')->whereIn('id',[$id])->get(); 
        foreach($receipts as $receipt){
            $receipt->printing_times += 1;
            $receipt->save();
        }
        return view('admin.receiptClients.print',compact('receipts'));
    }

    public function add_income(Request $request){ 
        $receipt = ReceiptClient::find($request->id);  
        return view('admin.receiptClients.partials.income',compact('receipt'));
    }

    public function branches(Request $request){ 
        $branches = RBranch::where('r_client_id',$request->id)->get();  
        return view('admin.receiptClients.partials.branches',compact('branches'));
    }

    public function permission_status(Request $request){
        $receipt = ReceiptClient::findOrFail($request->id);
        if($request->has('receive_premission')){
            $receipt->permission_status = 'receive_premission';
        }elseif($request->has('permission_complete')){
            $receipt->permission_status = 'permission_complete';
            $receipt->add_income();
        }
        $receipt->save();
        return redirect()->route('admin.receipt-clients.index');
    }

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $receipt = ReceiptClient::findOrFail($request->id);
        $receipt->$type = $request->status;
        if (($type == 'done') && $request->status == 1) {
            $receipt->quickly = 0; 
            $status = '2'; 
            if($receipt->branch){
                if($receipt->branch->payment_type == 'permissions'){
                    $status = '3';
                    $receipt->permission_status = 'deliverd';
                }elseif($receipt->branch->payment_type == 'cash'){ 
                    $receipt->add_income();
                }elseif($receipt->branch->payment_type == 'parts'){ 
                    if($receipt->branch->r_client->managment_type == 'seperate'){
                        $receipt->branch->remaining += $receipt->calc_total_cost();
                        $receipt->branch->save();
                    }else{
                        $receipt->branch->r_client->remaining += $receipt->calc_total_cost();
                        $receipt->branch->r_client->save();
                    }
                }
            } 
        }else{ 
            $status = '1';
        }
        
        $receipt->save();
        return [
            'status' => $status,
            'first' => '<i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>',
            'second' => view('admin.receiptClients.partials.permission_status',compact('receipt'))->render(),
        ];
    }

    public function duplicate($id){

        $receipt_client = ReceiptClient::findOrFail($id); 
        
        $new_receipt = new ReceiptClient; 
        $new_receipt->client_name = $receipt_client->client_name;
        $new_receipt->phone_number = $receipt_client->phone_number;
        $new_receipt->total_cost = $receipt_client->total_cost;
        $new_receipt->note = $receipt_client->note;
        $new_receipt->save();

        $receipt_products = ReceiptClientProductPivot::where('receipt_client_id',$receipt_client->id)->get();
        
        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_client_id = $new_receipt->id;
            $new_receipt_product->save();
        }
        alert('Receipt has been inserted successfully','','success');
        return redirect()->route('admin.receipt-clients.index');
    }

    public function view_products(Request $request){
        if($request->ajax()){
            $receipt = ReceiptClient::withTrashed()->find($request->id);
            $products = ReceiptClientProductPivot::where('receipt_client_id',$request->id)->latest()->get(); 
            return view('admin.receiptClients.partials.view_products',compact('products','receipt'));
        }else{
            return '';
        }
    }

    public function destroy_product($id)
    {
        $receipt_client_product_pivot = ReceiptClientProductPivot::find($id);
        $receipt = ReceiptClient::find($receipt_client_product_pivot->receipt_client_id); 

        $receipt_client_product_pivot->delete();

        $receipt_client_products = ReceiptClientProductPivot::where('receipt_client_id', $receipt->id)->get();
        $sum = 0;
        foreach ($receipt_client_products as $row) {
            $sum += $row->total_cost; 
        }
        $receipt->total_cost = $sum; 
        $receipt->save();

        // store the receipt social id in session so when redirect to the table open the popup to view products after delete
        session()->put('update_receipt_id',$receipt->id);

        toast(trans('flash.deleted'),'success'); 
        return 1;
    }
    public function edit_product(Request $request){
        if($request->ajax()){
            $receipt_client_product_pivot = ReceiptClientProductPivot::find($request->id); 
            $receipt = ReceiptClient::find($receipt_client_product_pivot->receipt_client_id);
            $products = ReceiptClientProduct::where('website_setting_id',$receipt->website_setting_id)->latest()->get();
            $price_type = $receipt->price_type();
            return view('admin.receiptClients.partials.edit_product',compact('receipt_client_product_pivot','products','price_type'));
        }else{ 

            $receipt_product_pivot = ReceiptClientProductPivot::find($request->receipt_product_pivot_id);
            $receipt = ReceiptClient::find($receipt_product_pivot->receipt_client_id);
            $price_type = $receipt->price_type();
            
            $product = ReceiptClientProduct::findOrFail($request->product_id); 

            $receipt_product_pivot->receipt_client_product_id = $request->product_id;
            $receipt_product_pivot->description = $product->name;
            $receipt_product_pivot->price = $product->$price_type;
            $receipt_product_pivot->quantity = $request->quantity;
            $receipt_product_pivot->total_cost = ($request->quantity * $product->$price_type);
            $receipt_product_pivot->save();

            // calculate the costing of products in receipt
            $all_receipt_product_pivot = ReceiptClientProductPivot::where('receipt_client_id', $receipt->id)->get();
            $sum = 0;
            foreach ($all_receipt_product_pivot as $row) {
                $sum += $row->total_cost;
            }

            // update the main receipt with new costing after calculation of its products
            $receipt->total_cost = $sum;
            $receipt->save(); 
            // store the receipt social id in session so when redirect to the table open the popup to view products after edit
            session()->put('update_receipt_id',$receipt->id);

            toast(trans('flash.global.update_title'),'success'); 
            return redirect()->route('admin.receipt-clients.index');
        }
    }

    public function add_product(Request $request){
        if($request->ajax()){
            $receipt = ReceiptClient::find($request->id);
            $products = ReceiptClientProduct::where('website_setting_id',$receipt->website_setting_id)->latest()->get();
            $receipt_id = $request->id;
            $order_num = $receipt->order_num;
            $price_type = $receipt->price_type();
            return view('admin.receiptClients.partials.add_product',compact('products','receipt_id','order_num','price_type'));
        }else{
            $receipt = ReceiptClient::find($request->receipt_id);  
            $price_type = $receipt->price_type();

            $product = ReceiptClientProduct::findOrFail($request->product_id);

            $receipt_product_pivot = new ReceiptClientProductPivot(); 
            $receipt_product_pivot->receipt_client_id = $request->receipt_id;
            $receipt_product_pivot->receipt_client_product_id = $request->product_id; 
            $receipt_product_pivot->description = $product->name;
            $receipt_product_pivot->price = $product->$price_type;
            $receipt_product_pivot->quantity = $request->quantity; 
            $receipt_product_pivot->total_cost = ($request->quantity * $product->$price_type);
            $receipt_product_pivot->save();
            
            $receipt_products = ReceiptClientProductPivot::where('receipt_client_id', $request->receipt_id)->get();
            $sum = 0;
            foreach ($receipt_products as $row) {
                $sum += $row->total_cost;
            }
            $receipt->total_cost = $sum;
            $receipt->save();

            if($request->has('add_more')){
                session()->put('store_receipt_id',$receipt->id);
                session()->put('update_receipt_id',null);
            }
            if($request->has('save_close')){
                session()->put('store_receipt_id',null);
                session()->put('update_receipt_id',$receipt->id);
            }

            toast(trans('flash.global.success_title'),'success');
            return redirect()->route('admin.receipt-clients.index');
        }
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get(); 
        $websites = WebsiteSetting::pluck('site_name', 'id');

        if($request->has('cancel_popup')){
            session()->put('store_receipt_id',null);
            session()->put('update_receipt_id',null);
        }

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
        $quickly = null;
        $done = null;
        $description = null; 
        $deleted = null; 
        $website_setting_id = null; 

        
        $enable_multiple_form_submit = true;

        if(request('deleted')){
            $deleted = 1; 
            $receipts = ReceiptClient::with(['staff:id,name','branch','incomes'])->onlyTrashed();  
        }else{
            $receipts = ReceiptClient::with(['staff:id,name','branch','incomes']);  
        }

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }
        
        if ($request->quickly != null) {
            $receipts = $receipts->where('quickly', $request->quickly);
            $quickly = $request->quickly;
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
            $receipts = $receipts->whereHas('receiptsReceiptClientProducts', function ($query) use ($description) {
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
            $receipts = $receipts->whereBetween('order_num', [ 'receipt-client#' . $from,  'receipt-client#' . $to]);
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
            $receipts = $receipts->with('receiptsReceiptClientProducts')->get();
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptClients.print', compact('receipts'));
        }
        
        if($request->has('download')){
            return Excel::download(new ReceiptClientExport($receipts->get()), 'client_receipts_('.$from.')_('.$to.')_('. $request->client_name .').xlsx');
        }
        
        $statistics = [  
            'total_deposit' => $receipts->sum('deposit'),
            'total_total_cost' => $receipts->sum('total_cost'),
        ];
        
        $receipts = $receipts->orderBy('quickly', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.receiptClients.index',compact(
            'staffs', 'phone', 'client_name', 'order_num', 'staff_id', 'from','websites',
            'to', 'from_date', 'to_date', 'date_type', 'exclude', 'include', 'quickly','enable_multiple_form_submit',
            'done', 'description', 'receipts', 'statistics','deleted','website_setting_id'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('receipt_client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $previous_data = searchByPhone($request->phone_number);

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rclients = RClient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $website_setting_id = $request->website_setting_id;

        return view('admin.receiptClients.create', compact('previous_data' , 'websites','website_setting_id','rclients'));
    }

    public function store(StoreReceiptClientRequest $request)
    {
        $receiptClient = ReceiptClient::create($request->all());

        // store the receipt social id in session so when redirect to the table open the popup to add products
        session()->put('store_receipt_id',$receiptClient->id);

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-clients.index');
    }

    public function edit(ReceiptClient $receiptClient)
    {
        abort_if(Gate::denies('receipt_client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient->load('staff');

        $websites = WebsiteSetting::pluck('site_name', 'id')->prepend(trans('global.pleaseSelect'), ''); 

        $rclients = RBranch::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptClients.edit', compact('receiptClient','websites','rclients'));
    }

    public function update(UpdateReceiptClientRequest $request, ReceiptClient $receiptClient)
    {
        $receiptClient->update($request->all());

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.receipt-clients.index');
    }

    public function show(ReceiptClient $receiptClient)
    {
        abort_if(Gate::denies('receipt_client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient->load('staff', 'receiptsReceiptClientProducts');

        return view('admin.receiptClients.show', compact('receiptClient'));
    } 

    public function destroy($id)
    {
        abort_if(Gate::denies('receipt_client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient = ReceiptClient::withTrashed()->find($id); 
        if($receiptClient->deleted_at != null){
            $receiptClient->forceDelete();
        }else{
            $receiptClient->delete();
        }
        

        alert(trans('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_client_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient = ReceiptClient::withTrashed()->find($id);
        $receiptClient->restore();

        alert(trans('flash.restored'),'','success');

        return redirect()->route('admin.receipt-clients.index');
    } 
}
