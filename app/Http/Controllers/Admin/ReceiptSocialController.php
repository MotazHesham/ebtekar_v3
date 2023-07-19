<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptSocialDeliveryExport;
use App\Exports\ReceiptSocialExport;
use App\Exports\ReceiptSocialResultsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WaslaController;
use App\Http\Requests\MassDestroyReceiptSocialRequest;
use App\Http\Requests\StoreReceiptSocialRequest;
use App\Http\Requests\UpdateReceiptSocialRequest;
use App\Imports\ReceiptSocialImport;
use App\Models\Country;
use App\Models\ExcelFile;
use App\Models\GeneralSetting;
use App\Models\ReceiptSocial; 
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\Social;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Storage;

class ReceiptSocialController extends Controller
{
    public function upload_fedex(Request $request){

        $now_time = time();
        $excelFile = new ExcelFile();
        $excelFile->type = 'social_delivery';
        
        $excelFile->addMedia(storage_path('tmp/uploads/' . basename($request->input('uploaded_file'))))->toMediaCollection('uploaded_file'); 

        $sheets = (new ReceiptSocialImport)->toCollection(storage_path('tmp/uploads/' . basename($request->input('uploaded_file'))));
        $accepted = [];
        $rejected = [];
        $countries = [];
        foreach(Country::all() as $country){
            if($country->code){
                $countries[$country->code] = $country->code_cost;
            }
        }
        foreach($sheets[0] as $key => $row){
            if($key != 0){
                $receipt_social = ReceiptSocial::where('order_num',$row[1])->first();
                if($receipt_social){
                    if($receipt_social->done){
                        $row[] = 'تم التسليم من قبل';
                        $rejected[] = $row;
                    }else{
                        $code_cost = $countries[$row[2]] ?? 0;
                        $row[] = $code_cost;
                        $row[] = $row[3] - $code_cost;
                        $accepted[] = $row;
                        $receipt_social->done = 1;
                        $receipt_social->save();
                    }
                }else{
                    $row [] = 'Not Found';
                    $rejected[] = $row;
                }
            }
        }
        $excelFile->results = json_encode([
            'accepted' => count($accepted),
            'rejected' => count($rejected),
        ]);

        $rows = [
            'accepted' => $accepted,
            'rejected' => $rejected,
        ]; 
        $path = 'tmp/uploads/'.$now_time . '_social_receipts_results.xlsx';
        Excel::store(new ReceiptSocialResultsExport($rows), $path);  
        $excelFile->addMedia(storage_path('app/' . $path))->toMediaCollection('result_file'); 
        $excelFile->save();
        return redirect()->route('admin.excel-files.index');

    }

    public function update_delivery_man(Request $request){ 
        $receipt = ReceiptSocial::find($request->row_id);
        $receipt->delivery_man_id = $request->delivery_man_id;
        $receipt->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $receipt->delivery_status = 'on_delivery'; 
        $receipt->save();
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-socials.index');
    }

    public function receive_money($id){
        $receipt = ReceiptSocial::find($id); 
        return view('partials.receive_money',compact('receipt'));
    }

    public function print($id){
        $receipts = ReceiptSocial::with('receiptsReceiptSocialProducts','staff','designer','manufacturer','preparer','shipmenter')->whereIn('id',[$id])->get(); 
        foreach($receipts as $receipt){
            $receipt->printing_times += 1;
            $receipt->save();
        }
        return view('admin.receiptSocials.print',compact('receipts'));
    }

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $receipt = ReceiptSocial::findOrFail($request->id);
        $receipt->$type = $request->status;
        if (in_array($request->type,['done','returned']) && $request->status == 1) {
            $receipt->quickly = 0;
            $receipt->delivery_status = $type == 'done' ? 'delivered' : 'cancel';
            $receipt->payment_status = $type == 'done' ? 'paid' : 'unpaid';
        }
        $receipt->save();
        return 1;
    }

    public function duplicate($id){

        $receipt_social = ReceiptSocial::findOrFail($id); 
        
        $new_receipt = new ReceiptSocial; 
        $new_receipt->client_name = $receipt_social->client_name;
        $new_receipt->phone_number = $receipt_social->phone_number;
        $new_receipt->phone_number_2 = $receipt_social->phone_number_2;
        $new_receipt->total_cost = $receipt_social->total_cost;
        $new_receipt->commission = $receipt_social->commission;
        $new_receipt->extra_commission = $receipt_social->extra_commission;
        $new_receipt->note = $receipt_social->note;
        $new_receipt->shipping_country_id = $receipt_social->shipping_country_id; 
        $new_receipt->shipping_country_cost = $receipt_social->shipping_country_cost;
        $new_receipt->shipping_address = $receipt_social->shipping_address;
        $new_receipt->client_type = $receipt_social->client_type;
        $new_receipt->save();

        $receipt_products = ReceiptSocialProductPivot::where('receipt_social_id',$receipt_social->id)->get();
        
        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_social_id = $new_receipt->id;
            $new_receipt_product->save();
        }
        alert('Receipt has been inserted successfully','','success');
        return redirect()->route('admin.receipt-socials.index');
    }

    public function view_products(Request $request){
        if($request->ajax()){
            $receipt = ReceiptSocial::withTrashed()->find($request->id);
            $products = ReceiptSocialProductPivot::where('receipt_social_id',$request->id)->latest()->get(); 
            return view('admin.receiptSocials.partials.view_products',compact('products','receipt'));
        }else{
            return '';
        }
    }

    public function destroy_product($id){
        $receipt_social_product_pivot = ReceiptSocialProductPivot::find($id);
        $receipt = ReceiptSocial::find($receipt_social_product_pivot->receipt_social_id);
        if (!auth()->user()->is_admin) {
            if (!$receipt->playlist_status == 'pending') {
                alert('لايمكن حذف منتج من هذه الفاتورة','','error');
                return 1;
            }
        }

        $receipt_social_product_pivot->delete();

        $receipt_social_products = ReceiptSocialProductPivot::where('receipt_social_id', $receipt->id)->get();
        $sum = 0;
        $sum2 = 0;
        $sum3 = 0;
        foreach ($receipt_social_products as $row) {
            $sum += $row->total_cost;
            $sum2 += $row->commission;
            $sum3 += ($row->extra_commission * $row->quantity);
        }
        $receipt->total_cost = $sum;
        $receipt->commission = $sum2;
        $receipt->extra_commission = $sum3;
        $receipt->save();

            
        // store the receipt social id in session so when redirect to the table open the popup to view products after delete
        session()->put('update_receipt_id',$receipt->id);

        toast(trans('flash.deleted'),'success'); 
        return 1;
    }

    public function edit_product(Request $request){
        if($request->ajax()){
            $receipt_social_product_pivot = ReceiptSocialProductPivot::find($request->id); 
            $receipt = ReceiptSocial::find($receipt_social_product_pivot->receipt_social_id);
            $products = ReceiptSocialProduct::where('website_setting_id',$receipt->website_setting_id)->latest()->get(); 
            return view('admin.receiptSocials.partials.edit_product',compact('receipt_social_product_pivot','products'));
        }else{ 

            $receipt_product_pivot = ReceiptSocialProductPivot::find($request->receipt_product_pivot_id);
            $receipt = ReceiptSocial::find($receipt_product_pivot->receipt_social_id);

            if (!auth()->user()->is_admin) {
                if (!$receipt->playlist_status == 'pending') {
                    alert('لايمكن تعديل منتج في هذه الفاتورة','','error');
                    return redirect()->route('receipt.receipt-socials.index');
                }
            }
            $product = ReceiptSocialProduct::findOrFail($request->product_id); 

            $receipt_product_pivot->receipt_social_product_id = $request->product_id;
            $receipt_product_pivot->title = $product->name;
            $receipt_product_pivot->description = $request->description;
            $receipt_product_pivot->price = $product->price;
            $receipt_product_pivot->quantity = $request->quantity;
            if($request->extra_commission != null){ 
                $receipt_product_pivot->extra_commission = $request->extra_commission;
            }
            $receipt_product_pivot->commission = ($request->quantity *  $product->commission);
            $receipt_product_pivot->total_cost = ($request->quantity * $product->price);

            if ($request->hasFile('pdf')) {
                $receipt_product_pivot->pdf= $request->pdf->store('uploads/receipt_social/pdf');
            }

            if ($request->has('previous_photos')) {
                $photos = $request->previous_photos;
            } else {
                $photos = array();
            }

            if ($request->has('photos')) { 
                foreach ($request->photos as $key => $photo) {
                    if(isset($photo['photo'])){
                        $new['photo'] = $photo['photo']->store('uploads/receipt_social/photos');
                        $new['note'] = $photo['note'];
                        array_push($photos,$new);
                    }
                }
            }     

            $receipt_product_pivot->photos = json_encode($photos);   

            $receipt_product_pivot->save();

            // calculate the costing of products in receipt
            $all_receipt_product_pivot = ReceiptSocialProductPivot::where('receipt_social_id', $receipt->id)->get();
            $sum = 0;
            $sum2 = 0;
            $sum3 = 0;
            foreach ($all_receipt_product_pivot as $row) {
                $sum += $row->total_cost;
                $sum2 += ($row->commission * $row->quantity);
                $sum3 += $row->extra_commission;
            }

            // update the main receipt with new costing after calculation of its products
            $receipt->total_cost = $sum;
            $receipt->commission = $sum2;
            $receipt->extra_commission = $sum3;
            $receipt->save(); 
            
            // store the receipt social id in session so when redirect to the table open the popup to view products after edit
            session()->put('update_receipt_id',$receipt->id);

            toast(trans('flash.global.update_title'),'success');
            return redirect()->route('admin.receipt-socials.index');
        }
    }

    public function add_product(Request $request){
        if($request->ajax()){
            $receipt = ReceiptSocial::find($request->id);
            $products = ReceiptSocialProduct::where('website_setting_id',$receipt->website_setting_id)->latest()->get();
            $receipt_id = $request->id;
            $order_num = $receipt->order_num;
            return view('admin.receiptSocials.partials.add_product',compact('products','receipt_id','order_num'));
        }else{  
            $receipt = ReceiptSocial::find($request->receipt_id);
            
            if (!auth()->user()->is_admin) {
                if (!$receipt->playlist_status == 'pending'){
                    alert('لايمكن أضافة منتج في هذه الفاتورة','','error');
                    return redirect()->route('admin.receipt-socials.index');
                }
            }

            $product = ReceiptSocialProduct::findOrFail($request->product_id);

            $receipt_product_pivot = new ReceiptSocialProductPivot(); 
            $receipt_product_pivot->receipt_social_id = $request->receipt_id;
            $receipt_product_pivot->receipt_social_product_id = $request->product_id;
            $receipt_product_pivot->title = $product->name;
            $receipt_product_pivot->description = $request->description;
            $receipt_product_pivot->price = $product->price;
            $receipt_product_pivot->quantity = $request->quantity;
            $receipt_product_pivot->commission = ($request->quantity *  $product->commission);
            $receipt_product_pivot->total_cost = ($request->quantity * $product->price);

            if ($request->hasFile('pdf')) {
                $receipt_product_pivot->pdf = $request->pdf->store('uploads/receipt_social/pdf'); 
            }

            $photos = array();

            if ($request->has('photos')) {
                foreach ($request->photos as $key => $photo) {
                    if(isset($photo['photo'])){
                        $photos[$key]['photo'] = $photo['photo']->store('uploads/receipt_social/photos'); 
                        $photos[$key]['note'] = $photo['note'];
                    }
                }
                $receipt_product_pivot->photos = json_encode($photos);
            }  
            $receipt_product_pivot->save();
            
            $receipt_products = ReceiptSocialProductPivot::where('receipt_social_id', $request->receipt_id)->get();
            $sum = 0;
            $sum2 = 0;
            $sum3 = 0;
            foreach ($receipt_products as $row) {
                $sum += $row->total_cost;
                $sum2 += $row->commission;
                $sum3 += $row->extra_commission;
            }
            $receipt->total_cost = $sum;
            $receipt->commission = $sum2;
            $receipt->extra_commission = $sum3;
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
            return redirect()->route('admin.receipt-socials.index');
        }
    }

    public function index(Request $request){ 
        abort_if(Gate::denies('receipt_social_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get();
        $delivery_mans = User::where('user_type', 'delivery_man')->get();
        $socials = Social::all();
        $countries = Country::where('status',1)->get()->groupBy('type'); 
        $websites = WebsiteSetting::pluck('site_name', 'id');
        
        if($request->has('cancel_popup')){
            session()->put('store_receipt_id',null);
            session()->put('update_receipt_id',null);
        }

        $phone = null;
        $client_name = null;
        $order_num = null;
        $client_type = null;
        $delivery_status = null;
        $payment_status = null;
        $staff_id = null;
        $delivery_man_id = null;
        $from = null;
        $to = null;
        $from_date = null;
        $to_date = null;
        $date_type = null;
        $social_id = null;
        $exclude = null;
        $include = null;
        $sent_to_delivery = null; 
        $confirm = null; 
        $quickly = null;
        $done = null;
        $returned = null;
        $country_id = null;
        $playlist_status = null;
        $description = null; 
        $deleted = null;
        $website_setting_id = null;

        $enable_multiple_form_submit = true;

        if(request('deleted')){
            $deleted = 1;
            $receipts = ReceiptSocial::with(['staff:id,name','delivery_man:id,name', 'socials','shipping_country'])->withCount('receiptsReceiptSocialProducts')->onlyTrashed(); 
        }else{
            $receipts = ReceiptSocial::with(['staff:id,name','delivery_man:id,name', 'socials','shipping_country'])->withCount('receiptsReceiptSocialProducts'); 
        }

        if ($request->client_type != null) {
            $receipts = $receipts->where('client_type', $request->client_type);
            $client_type = $request->client_type;
        }
        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $receipts = $receipts->where('shipping_country_id', $country_id);
        }

        if ($request->sent_to_delivery != null) {
            $sent_to_delivery = $request->sent_to_delivery;
            if($sent_to_delivery){
                $receipts = $receipts->whereNotNull('send_to_delivery_date');
            }else{
                $receipts = $receipts->whereNull('send_to_delivery_date');
            }
        }
        if ($request->social_id != null) {
            $social_id = $request->social_id;
            $GLOBALS['social_id'] = $social_id;
            $receipts = $receipts->whereHas('socials', function ($q) {
                $q->where('id', $GLOBALS['social_id']);
            });
        }

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }

        if ($request->confirm != null) {
            $receipts = $receipts->where('confirm', $request->confirm);
            $confirm = $request->confirm;
        }

        if ($request->returned != null) {
            $receipts = $receipts->where('returned', $request->returned);
            $returned = $request->returned;
        }

        if ($request->playlist_status != null) {
            $receipts = $receipts->where('playlist_status', $request->playlist_status);
            $playlist_status = $request->playlist_status;
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

        if ($request->delivery_man_id != null) {
            $receipts = $receipts->where('delivery_man_id', $request->delivery_man_id);
            $delivery_man_id = $request->delivery_man_id;
        }

        if ($request->description != null) {
            $description = $request->description;
            $receipts = $receipts->whereHas('receiptsReceiptSocialProducts', function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }
        if ($request->phone != null) {
            global $phone;
            $phone = $request->phone;
            $receipts = $receipts->where(function ($query) {
                $query->where('phone_number', 'like', '%' . $GLOBALS['phone'] . '%')
                    ->orWhere('phone_number_2', 'like', '%' . $GLOBALS['phone'] . '%');
            });
        }
        if ($request->client_name != null) {
            $receipts = $receipts->where('client_name', 'like', '%' . $request->client_name . '%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null) {
            $receipts = $receipts->where('order_num', 'like', '%' . $request->order_num . '%');
            $order_num = $request->order_num;
        }
        if ($request->delivery_status != null) {
            $receipts = $receipts
                ->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->payment_status != null) {
            $receipts = $receipts
                ->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->from != null && $request->to != null) {
            $from = $request->from;
            $to = $request->to;
            $receipts = $receipts->whereBetween('order_num', [ 'receipt-social#' . $from,  'receipt-social#' . $to]);
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

        if ($request->has('download')) {
            return Excel::download(new ReceiptSocialExport($receipts->with('receiptsReceiptSocialProducts')->get()), 'social_receipts_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        }

        if ($request->has('download_delivery')) {
            return Excel::download(new ReceiptSocialDeliveryExport($receipts->with('receiptsReceiptSocialProducts')->get()), 'social_receipts_delivery_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        }

        if ($request->has('print')) {
            $receipts = $receipts->with('receiptsReceiptSocialProducts','designer','manufacturer','preparer','shipmenter')->get();
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptSocials.print', compact('receipts'));
        }
        
        $statistics = [
            'total_commission' => $receipts->sum('commission') + $receipts->sum('extra_commission'),
            'total_shipping_country_cost' => $receipts->sum('shipping_country_cost'),
            'total_deposit' => $receipts->sum('deposit'),
            'total_total_cost' => $receipts->sum('total_cost') + $receipts->sum('extra_commission'),
        ];

        $receipts = $receipts->orderBy('quickly', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.receiptSocials.index', compact(
            'countries', 'statistics','receipts','done','client_type','exclude','enable_multiple_form_submit',
            'delivery_status','payment_status','sent_to_delivery','social_id','websites','website_setting_id',
            'country_id','returned','date_type','phone','client_name','order_num', 'deleted',
            'quickly','playlist_status','description', 'include','socials','delivery_mans',
            'delivery_man_id','staff_id','from','to','from_date','to_date', 'staffs','confirm',  
        ));
    }

    
    public function send_to_wasla(Request $request){
        $receipt = ReceiptSocial::findOrFail($request->row_id);
        $company_id = Auth::user()->wasla_company_id;

        $receipt_products_pivot = ReceiptSocialProductPivot::where('receipt_social_id', $request->row_id)->orderBy('updated_at', 'desc')->get();

        $description = '';
        $note = '';
        foreach ($receipt_products_pivot as $raw) {
            $description .= $raw->title;
            $description .= ' <br> ';

            $note .= $raw->description;
            $note .= ' <br> ';
        }

        $data = [
            //from receipt
            'company_id' => $company_id,
            'receiver_name' => $receipt->client_name,
            'phone_1' => $receipt->phone_number,
            'phone_2' => $receipt->phone_number_2,
            'address' => $receipt->shipping_address,
            'description' => $description,
            'note' => $note,
            'receipt_code' => $receipt->order_num,

            //from form
            'district' => $request->district,
            'type' => $request->type,
            'cost' => $request->cost,
            'in_return_case' => $request->in_return_case,
            'country_id' => $request->country_id,
            'status' => $request->status,
        ];

        $waslaController = new WaslaController;
        $response = $waslaController->store_order($data);

        if ($response) {
            if ($response['errNum'] == 200) {
                $receipt->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
                $receipt->delivery_status = 'on_delivery'; 
                $receipt->save();
                alert('تم أرسال الأوردر لواصلة بنجاح');
            } elseif ($response['errNum'] == 401) {
                alert('',$response['msg'],'error'); 
            } else {
                alert('SomeThing Went Wrong000','','error');
            }
        } else {
            alert('SomeThing Went Wrong','','error');
        }
        return redirect()->route('admin.receipt-socials.index');
    }

    public function create(Request $request)
    {   
        abort_if(Gate::denies('receipt_social_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $shipping_countries = Country::select('cost','name', 'id')->get();

        $socials = Social::pluck('name', 'id');

        $previous_data = searchByPhone($request->phone_number);

        $website_setting_id = $request->website_setting_id;

        $websites = WebsiteSetting::pluck('site_name', 'id');
        
        return view('admin.receiptSocials.create', compact('shipping_countries', 'socials', 'previous_data' , 'websites','website_setting_id'));
    }

    public function store(StoreReceiptSocialRequest $request)
    { 
        $receiptSocial = ReceiptSocial::create($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        // store the receipt social id in session so when redirect to the table open the popup to add products
        session()->put('store_receipt_id',$receiptSocial->id);

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-socials.index');
    }

    public function edit(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $site_settings = get_site_setting(); 

        $shipping_countries = Country::select('cost','name', 'id')->get();

        $socials = Social::pluck('name', 'id'); 

        $receiptSocial->load('delivery_man', 'shipping_country', 'socials');

        if($site_settings->delivery_system == 'wasla'){
            $waslaController = new WaslaController;
            $response = $waslaController->countries();
        }else{
            $response = '';
        } 

        return view('admin.receiptSocials.edit', compact('receiptSocial', 'shipping_countries', 'socials', 'site_settings', 'response'));
    }

    public function update(UpdateReceiptSocialRequest $request, ReceiptSocial $receiptSocial)
    {
        $receiptSocial->update($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.receipt-socials.index');
    }

    public function show(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country', 'socials', 'receiptsReceiptSocialProducts');

        return view('admin.receiptSocials.show', compact('receiptSocial'));
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('receipt_social_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial = ReceiptSocial::withTrashed()->find($id); 
        if($receiptSocial->deleted_at != null){
            $receiptSocial->forceDelete();
        }else{
            $receiptSocial->delete();
        }
        

        alert(trans('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_social_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial = ReceiptSocial::withTrashed()->find($id);
        $receiptSocial->restore();

        alert(trans('flash.restored'),'','success');

        return redirect()->route('admin.receipt-socials.index');
    } 
}
