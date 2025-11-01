<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptSocialDeliveryExport;
use App\Exports\ReceiptSocialExport;
use App\Exports\ReceiptSocialResultsExport;
use App\Exports\CustomerReportExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WaslaController;
use App\Http\Requests\MassDestroyReceiptSocialRequest;
use App\Http\Requests\StoreReceiptSocialRequest;
use App\Http\Requests\UpdateReceiptSocialRequest;
use App\Imports\ReceiptSocialImport;
use App\Models\Country;
use App\Models\ExcelFile;
use App\Models\FinancialAccount;
use App\Models\GeneralSetting;
use App\Models\ReceiptSocial; 
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\Social;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
        foreach($sheets[0] as $key => $row){
            if($key != 0){
                $receipt_social = ReceiptSocial::where('order_num',$row[1])->first();
                if($receipt_social){
                    $code_cost = $receipt_social->shipping_country->code_cost ?? 0;
                    if($request->type == 'done'){
                        if($receipt_social->done){
                            $row[] = 'تم التسليم من قبل';
                            $rejected[] = $row;
                        }else{ 
                            $row[] = $code_cost;
                            $row[] = $row[3] - $code_cost;
                            $accepted[] = $row;
                            $receipt_social->done = 1;
                            $receipt_social->save();
                        }
                    }elseif($request->type == 'supplied'){
                        if($receipt_social->supplied){
                            $row[] = 'تم التوريد من قبل';
                            $rejected[] = $row;
                        }else{ 
                            $row[] = $code_cost;
                            $row[] = $row[3] - $code_cost;
                            $accepted[] = $row;
                            $receipt_social->supplied = 1;
                            $receipt_social->save();
                            $receipt_social->add_income();
                        }
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

        $excelFile->type2 = $request->type;
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
        toast(__('flash.global.success_title'),'success');
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
        $status = '1';
        if (in_array($request->type,['done','returned']) && $request->status == 1) {
            $receipt->quickly = 0;
            $receipt->delivery_status = $type == 'done' ? 'delivered' : 'cancel';
            $receipt->payment_status = $type == 'done' ? 'paid' : 'unpaid';

            if($type == 'done'){
                $receipt->done_time = date(config('panel.date_format') . ' ' . config('panel.time_format'));
            }
        }
        
        if (($type == 'supplied') && $request->status == 1) {
            $status = '2';
            $receipt->add_income();
        } 
        if($request->type == 'hold'){
            $receipt->hold_reason = $request->hold_reason;
        }

        $receipt->save();
        if($request->ajax()){
            return [
                'status' => $status,
                'first' => '<i class="far fa-check-circle" style="padding: 5px; font-size: 20px; color: green;"></i>', 
                'message' => '',
            ];
        }else{
            return redirect()->back();
        }
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
        $new_receipt->website_setting_id = $receipt_social->website_setting_id;
        $new_receipt->save();

        $receipt_products = ReceiptSocialProductPivot::where('receipt_social_id',$receipt_social->id)->get();
        
        foreach($receipt_products as $row){
            $new_receipt_product = $row->replicate();
            $new_receipt_product->receipt_social_id = $new_receipt->id;
            $new_receipt_product->save();
        }
        alert('Receipt has been inserted successfully','','success');
        return redirect()->back();
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
            if ($receipt->playlist_status != 'pending') {
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
        session()->put('update_receipt_socail_id',$receipt->id);

        toast(__('flash.deleted'),'success'); 
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
                if ($receipt->playlist_status != 'pending') {
                    alert('لايمكن تعديل منتج في هذه الفاتورة','','error');
                    return redirect()->back();
                }
            }
            $product = ReceiptSocialProduct::findOrFail($request->product_id); 

            $receipt_product_pivot->receipt_social_product_id = $request->product_id;
            $receipt_product_pivot->title = $product->name;
            $receipt_product_pivot->description = $request->description;
            if (!auth()->user()->is_admin) {
                $receipt_product_pivot->price = $product->price;
                $receipt_product_pivot->total_cost = ($request->quantity * $product->price);
            }else{
                $receipt_product_pivot->price = $request->price;
                $receipt_product_pivot->total_cost = ($request->quantity * $request->price);
            }
            $receipt_product_pivot->quantity = $request->quantity;
            if($request->extra_commission != null){ 
                $receipt_product_pivot->extra_commission = $request->extra_commission;
            }
            $receipt_product_pivot->commission = ($request->quantity *  $product->commission);

            // Prepare existing PDFs
            $existingPdfs = [];
            if (!empty($receipt_product_pivot->pdf)) {
                $existingPdfs = json_decode($receipt_product_pivot->pdf, true) ?: [];
            }

            // Remove selected PDFs
            if ($request->has('remove_pdfs')) {
                $toRemove = (array) $request->input('remove_pdfs');
                $existingPdfs = array_values(array_filter($existingPdfs, function ($pdfPath) use ($toRemove) {
                    return !in_array($pdfPath, $toRemove);
                }));
                foreach ($toRemove as $pdfPath) {
                    if (Storage::exists($pdfPath)) {
                        Storage::delete($pdfPath);
                    }
                }
            }

            // Append newly uploaded PDFs
            if ($request->hasFile('pdf')) {
                foreach ($request->file('pdf') as $pdf_raw) {
                    if ($pdf_raw) {
                        $existingPdfs[] = $pdf_raw->store('uploads/receipt_social/pdf');
                    }
                }
            }

            // Persist PDFs array (or null if empty)
            $receipt_product_pivot->pdf = !empty($existingPdfs) ? json_encode($existingPdfs) : null;

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
            if(!$receipt->is_seasoned){
                $receipt->is_seasoned = $product->product_type == 'season' ? 1 : 0;
            }
            $receipt->save(); 
            
            // store the receipt social id in session so when redirect to the table open the popup to view products after edit
            session()->put('update_receipt_socail_id',$receipt->id);

            toast(__('flash.global.update_title'),'success');
            return redirect()->back();
        }
    }

    public function add_product(Request $request){
        if($request->ajax()){
            $receipt = ReceiptSocial::findOrFail($request->id);
            $products = ReceiptSocialProduct::where('website_setting_id',$receipt->website_setting_id)->latest()->get();
            $receipt_id = $request->id;
            $order_num = $receipt->order_num;
            return view('admin.receiptSocials.partials.add_product',compact('products','receipt_id','order_num'));
        }else{  
            $receipt = ReceiptSocial::findOrFail($request->receipt_id);
            
            if (!auth()->user()->is_admin) {
                if ($receipt->playlist_status != 'pending'){
                    alert('لايمكن أضافة منتج في هذه الفاتورة','','error');
                    return redirect()->back();
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
                $pdfs = array();
                foreach ($request->pdf as $key => $pdf_raw) {
                    $pdfs[] = $pdf_raw->store('uploads/receipt_social/pdf');
                }
                $receipt_product_pivot->pdf = json_encode($pdfs);
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
            if(!$receipt->is_seasoned){
                $receipt->is_seasoned = $product->product_type == 'season' ? 1 : 0;
            }

            if($product->has_shipping_offer){ 
                $zone = Zone::whereHas('countries',function($q) use($receipt){
                    $q->where('country_id',$receipt->shipping_country_id);
                })->first();
                if($zone){
                    $receipt->shipping_country_cost = $zone->delivery_cost_offer;
                }
            }
            $receipt->save();
            if($request->has('add_more')){
                session()->put('store_receipt_socail_id',$receipt->id);
                session()->put('update_receipt_socail_id',null);
            }
            if($request->has('save_close')){
                session()->put('store_receipt_socail_id',null);
                session()->put('update_receipt_socail_id',$receipt->id);
            }
            toast(__('flash.global.success_title'),'success');
            return redirect()->back();
        }
    }

    public function index(Request $request){ 
        abort_if(Gate::denies('receipt_social_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get();
        $delivery_mans = User::where('user_type', 'delivery_man')->get();
        $socials = Social::all();
        $countries = Country::where('status',1)->get()->groupBy('type'); 
        $websites = WebsiteSetting::pluck('site_name', 'id');
        $financial_accounts = FinancialAccount::get();
        $receiptSocialProducts = ReceiptSocialProduct::all();
        $zones = Zone::all();
        
        if($request->has('cancel_popup')){
            session()->put('store_receipt_socail_id',null);
            session()->put('update_receipt_socail_id',null);
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
        $supplied = null;
        $returned = null;
        $country_id = null;
        $playlist_status = null;
        $description = null; 
        $deleted = null;
        $deposit_type = null;
        $total_cost = null;
        $financial_account_id = null;
        $website_setting_id = null;
        $product_type = null;
        $isShopify = null;
        $isHold = null;
        $enable_multiple_form_submit = true;
        $general_search = null;
        $selectedProducts = null;
        $zone_id = null;
        $has_followup = null;

        if(request('deleted')){ 
            abort_if(Gate::denies('soft_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $deleted = 1;
            $receipts = ReceiptSocial::with(['staff:id,name','delivery_man:id,name', 'socials','shipping_country','financial_account'])->withCount('receiptsReceiptSocialProducts')->withCount('followups')->onlyTrashed(); 
        }else{
            $receipts = ReceiptSocial::with(['staff:id,name','delivery_man:id,name', 'socials','shipping_country','financial_account'])->withCount('receiptsReceiptSocialProducts')->withCount('followups'); 
        }
        if($request->general_search != null){
            $general_search = $request->general_search;
            $receipts = $receipts->where(function ($query) use ($general_search) {
                $query->where('order_num', 'like', '%' . $general_search . '%')
                    ->orWhere('phone_number', 'like', '%' . $general_search . '%')
                    ->orWhere('phone_number_2', 'like', '%' . $general_search . '%')
                    ->orWhere('client_name', 'like', '%' . $general_search . '%'); 
            });
        }

        if ($request->selectedProducts != null) {
            $selectedProducts = $request->selectedProducts;
            $receipts = $receipts->whereHas('receiptsReceiptSocialProducts', function ($query) use ($selectedProducts) {
                $query->whereIn('receipt_social_product_id', $selectedProducts);
            });
        }

        if ($request->client_type != null) {
            $receipts = $receipts->where('client_type', $request->client_type);
            $client_type = $request->client_type;
        }
        if ($request->has_followup != null) {
            $has_followup = $request->has_followup;
            if($has_followup == 1){
                $receipts = $receipts->whereHas('followups');
            }else{
                $receipts = $receipts->whereDoesntHave('followups');
            }
        }
        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $receipts = $receipts->where('shipping_country_id', $country_id);
        }
        if ($request->isHold != null) {
            $isHold = $request->isHold;
            $receipts = $receipts->where('hold', $isHold);
        }
        if ($request->zone_id != null) {
            $zone_id = $request->zone_id;
            $zone = Zone::with('countries')->find($zone_id);
            $receipts = $receipts->whereIn('shipping_country_id', $zone->countries->pluck('id'));
        }
        if ($request->isShopify != null) {
            $isShopify = $request->isShopify;
            if($isShopify == 1){
                $receipts = $receipts->whereNotNull('shopify_id');
            }else{
                $receipts = $receipts->whereNull('shopify_id');
            }
        }

        if ($request->sent_to_delivery != null) {
            $sent_to_delivery = $request->sent_to_delivery;
            if($sent_to_delivery){
                $receipts = $receipts->whereNotNull('send_to_delivery_date');
            }else{
                $receipts = $receipts->whereNull('send_to_delivery_date');
            }
        }

        if ($request->product_type != null) {
            $product_type = $request->product_type;
            if($product_type == 1){
                $receipts = $receipts->whereHas('receiptsReceiptSocialProducts.products', function($query) {
                    $query->where('product_type', 'season');
                });
            }else{
                $receipts = $receipts->whereHas('receiptsReceiptSocialProducts.products', function($query) {
                    $query->where('product_type', '!=', 'season')->orWhereNull('product_type');
                });
            }
        }

        if ($request->social_id != null) {
            $social_id = $request->social_id;
            $GLOBALS['social_id'] = $social_id;
            $receipts = $receipts->whereHas('socials', function ($q) {
                $q->where('id', $GLOBALS['social_id']);
            });
        }

        if ($request->deposit_type != null) {
            $receipts = $receipts->where('deposit_type', $request->deposit_type);
            $deposit_type = $request->deposit_type;
        }

        if ($request->total_cost != null) {
            $receipts = $receipts->where('total_cost','>=', $request->total_cost);
            $total_cost = $request->total_cost;
        }

        if ($request->financial_account_id != null) {
            $receipts = $receipts->where('financial_account_id', $request->financial_account_id);
            $financial_account_id = $request->financial_account_id;
        }

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }

        if ($request->supplied != null) {
            $receipts = $receipts->where('supplied', $request->supplied);
            $supplied = $request->supplied;
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
                foreach(explode(' ',$inc) as $inccc){
                    $include2[] = $inccc;
                }
            }
            $receipts = $receipts->where(function ($query) use($include2) {
                for ($i = 0; $i < count($include2); $i++){
                    $query->orwhere('order_num', 'like',  '%#' . $include2[$i]);
                }      
            });
        }

        if ($request->has('download')) {
            return Excel::download(new ReceiptSocialExport($receipts->with('receiptsReceiptSocialProducts')->get()), 'social_receipts_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        }

        if ($request->has('download_delivery_fedex')) {
            return Excel::download(new ReceiptSocialDeliveryExport($receipts->with('receiptsReceiptSocialProducts')->get(),'fedex'), 'social_receipts_delivery_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        }

        if ($request->has('download_delivery_smsa')) {
            return Excel::download(new ReceiptSocialDeliveryExport($receipts->with('receiptsReceiptSocialProducts')->get(),'smsa'), 'social_receipts_delivery_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        }

        if ($request->has('print')) {
            $receipts = $receipts->with('receiptsReceiptSocialProducts','designer','manufacturer','preparer','shipmenter')->get();
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptSocials.print', compact('receipts'));
        }
        
        
        // Clone the query for statistics
        $statisticsQuery = clone $receipts;

        // Perform all aggregations in a single query
        $statisticsData = $statisticsQuery->selectRaw('
                SUM(commission) as total_commission,
                SUM(extra_commission) as total_extra_commission,
                SUM(shipping_country_cost) as total_shipping_country_cost,
                SUM(deposit) as total_deposit,
                SUM(total_cost) as total_total_cost,
                SUM(discounted_amount) as total_discounted_amount
            ')->first();
        
        $statistics = [
            'total_total_cost_without_deposit' => $statisticsData->total_total_cost + $statisticsData->total_extra_commission - $statisticsData->total_deposit - $statisticsData->total_discounted_amount,
            'total_shipping_country_cost' => $statisticsData->total_shipping_country_cost,
            'total_deposit' => $statisticsData->total_deposit,
            'total_total_cost' => $statisticsData->total_total_cost + $statisticsData->total_extra_commission - $statisticsData->total_discounted_amount,
            'total_grand_total' => $statisticsData->total_total_cost + $statisticsData->total_extra_commission - $statisticsData->total_deposit + $statisticsData->total_shipping_country_cost - $statisticsData->total_discounted_amount,
        ];

        $receipts = $receipts->orderBy('quickly', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        if($request->has('new_design')){
            return view('admin.receiptSocials.index_modern', compact('countries', 'statistics','receipts','done','client_type','exclude','enable_multiple_form_submit',
            'delivery_status','payment_status','sent_to_delivery','social_id','websites','website_setting_id','total_cost',
            'country_id','returned','date_type','phone','client_name','order_num', 'deleted','financial_accounts','product_type', 
            'quickly','playlist_status','description', 'include','socials','delivery_mans','deposit_type','supplied','isShopify', 'zones', 'zone_id',
            'delivery_man_id','staff_id','from','to','from_date','to_date', 'staffs','confirm',  'financial_account_id','general_search','receiptSocialProducts','selectedProducts','has_followup'));
        }

        return view('admin.receiptSocials.index', compact(
            'countries', 'statistics','receipts','done','client_type','exclude','enable_multiple_form_submit',
            'delivery_status','payment_status','sent_to_delivery','social_id','websites','website_setting_id','total_cost',
            'country_id','returned','date_type','phone','client_name','order_num', 'deleted','financial_accounts','product_type',
            'quickly','playlist_status','description', 'include','socials','delivery_mans','deposit_type','supplied','isShopify', 'zones', 'zone_id',
            'delivery_man_id','staff_id','from','to','from_date','to_date', 'staffs','confirm',  'financial_account_id','general_search','receiptSocialProducts','selectedProducts','has_followup'
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

        $financial_accounts = FinancialAccount::where('active',1)->get();

        $previous_data = searchByPhone($request->phone_number);

        $website_setting_id = $request->website_setting_id;

        $websites = WebsiteSetting::pluck('site_name', 'id');
        
        return view('admin.receiptSocials.create', compact('shipping_countries', 'socials', 'previous_data' , 'websites','website_setting_id','financial_accounts'));
    }

    public function store(StoreReceiptSocialRequest $request)
    { 
        $receiptSocial = ReceiptSocial::create($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        // store the receipt social id in session so when redirect to the table open the popup to add products
        session()->put('store_receipt_socail_id',$receiptSocial->id);

        toast(__('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-socials.index');
    }

    public function edit(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $site_settings = get_site_setting(); 

        $shipping_countries = Country::select('cost','name', 'id')->get();

        $socials = Social::pluck('name', 'id'); 

        $financial_accounts = FinancialAccount::where('active',1)->get();

        $receiptSocial->load('delivery_man', 'shipping_country', 'socials');

        if($site_settings->delivery_system == 'wasla'){
            $waslaController = new WaslaController;
            $response = $waslaController->countries();
        }else{
            $response = '';
        } 

        return view('admin.receiptSocials.edit', compact('receiptSocial', 'shipping_countries', 'socials', 'site_settings', 'response','financial_accounts'));
    }

    public function update(UpdateReceiptSocialRequest $request, ReceiptSocial $receiptSocial)
    {
        if (!auth()->user()->is_admin) { 
            if ($receiptSocial->playlist_status != 'pending'){
                alert('لايمكن التعديل في هذه الفاتورة','','error');
                return redirect()->back();
            }
        }
        $receiptSocial->update($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        toast(__('flash.global.update_title'),'success');
        
        if($request->has('refresh')){
            return redirect()->route('admin.receipt-socials.edit', $receiptSocial->id);
        }

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
        if($receiptSocial->playlist_status != 'pending'){ 
            if(!auth()->user()->is_admin){ 
                alert('Cant delete','Contact Ur Adminstrator','warning'); 
                return 1;
            }
        }
        if($receiptSocial->deleted_at != null){
            $receiptSocial->forceDelete();
        }else{
            $receiptSocial->delete();
        } 

        alert(__('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_social_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial = ReceiptSocial::withTrashed()->find($id);
        $receiptSocial->restore();

        alert(__('flash.restored'),'','success');

        return redirect()->route('admin.receipt-socials.index');
    } 

    public function customerReport()
    {
        return Excel::download(new CustomerReportExport(), 'customer_report.xlsx');
    }

    public function customerChart()
    {
        // Get all receipts and group by phone number to count orders per customer
        $customerOrders = ReceiptSocial::select('phone_number', DB::raw('count(*) as order_count'))
            ->whereNotNull('phone_number')
            ->groupBy('phone_number')
            ->having('order_count', '>', 1)
            ->get()
            ->groupBy('order_count')
            ->map(function($group) {
                return $group->count();
            });

        // Prepare data for chart
        $labels = [];
        $values = [];
        
        // Sort by order count
        $customerOrders = $customerOrders->sortKeys();
        
        foreach($customerOrders as $orderCount => $customerCount) {
            $labels[] = $orderCount . ' طلبات';
            $values[] = $customerCount;
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
