<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptCompanyExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Controllers\WaslaController;
use App\Http\Requests\MassDestroyReceiptCompanyRequest;
use App\Http\Requests\StoreReceiptCompanyRequest;
use App\Http\Requests\UpdateReceiptCompanyRequest;
use App\Models\Country; 
use App\Models\ReceiptCompany;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptCompanyController extends Controller
{
    use MediaUploadingTrait;

    public function update_delivery_man(Request $request){ 
        $receipt = ReceiptCompany::find($request->row_id);
        $receipt->delivery_man_id = $request->delivery_man_id;
        $receipt->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $receipt->delivery_status = 'on_delivery'; 
        $receipt->save();
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-companies.index');
    } 

    public function print($id){
        $receipts = ReceiptCompany::with('staff','designer','manufacturer','preparer','shipmenter')->whereIn('id',[$id])->get();
        foreach($receipts as $receipt){
            $receipt->printing_times += 1;
            $receipt->save();
        }
        return view('admin.receiptCompanies.print',compact('receipts'));
    }

    public function update_statuses(Request $request){ 
        $type = $request->type;
        $receipt = ReceiptCompany::findOrFail($request->id);
        $receipt->$type = $request->status;
        if ($type == 'done' && $request->status == 1) {
            $receipt->quickly = 0;
            $receipt->delivery_status = $type == 'done' ? 'delivered' : 'cancel';
            $receipt->payment_status = $type == 'done' ? 'paid' : 'unpaid';
        }
        $receipt->save();
        return 1;
    }

    public function duplicate($id){

        $receipt_social = ReceiptCompany::findOrFail($id); 
        
        $new_receipt = new ReceiptCompany; 
        $new_receipt->client_name = $receipt_social->client_name;
        $new_receipt->phone_number = $receipt_social->phone_number;
        $new_receipt->phone_number_2 = $receipt_social->phone_number_2;
        $new_receipt->total_cost = $receipt_social->total_cost;
        $new_receipt->note = $receipt_social->note;
        $new_receipt->shipping_country_id = $receipt_social->shipping_country_id; 
        $new_receipt->shipping_country_cost = $receipt_social->shipping_country_cost;
        $new_receipt->shipping_address = $receipt_social->shipping_address;
        $new_receipt->client_type = $receipt_social->client_type;
        $new_receipt->description = '';
        $new_receipt->save();
        
        alert('Receipt has been inserted successfully','','success');
        return redirect()->route('admin.receipt-compaines.index');
    }

    public function send_to_wasla(Request $request){
        $receipt = ReceiptCompany::findOrFail($request->row_id);
        $company_id = Auth::user()->wasla_company_id; 
        
        $data = [
            //from receipt
            'company_id' => $company_id,
            'receiver_name' => $receipt->client_name,
            'phone_1' => $receipt->phone_number,
            'phone_2' => $receipt->phone_number_2,
            'address' => $receipt->shipping_address,
            'description' => html_entity_decode(strip_tags(nl2br($receipt->description ?? '...'))),
            'note' => $receipt->note,
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
        return redirect()->route('admin.receipt-companies.index');
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staffs = User::whereIn('user_type', ['staff', 'admin'])->get();
        $delivery_mans = User::where('user_type', 'delivery_man')->get(); 
        $countries = Country::where('status',1)->get()->groupBy('type'); 
        
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
        $exclude = null;
        $include = null;
        $sent_to_delivery = null;
        $calling = null;
        $quickly = null;
        $done = null;
        $no_answer = null;
        $country_id = null;
        $playlist_status = null;
        $description = null; 
        $confirm = null; 
        $deleted = null;

        $enable_multiple_form_submit = true;
        
        if(request('deleted')){
            $deleted = 1;
            $receipts = ReceiptCompany::with(['staff:id,name','delivery_man:id,name','shipping_country'])->onlyTrashed(); 
        }else{
            $receipts = ReceiptCompany::with(['staff:id,name','delivery_man:id,name','shipping_country']); 
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

        if ($request->done != null) {
            $receipts = $receipts->where('done', $request->done);
            $done = $request->done;
        }

        if ($request->calling != null) {
            $receipts = $receipts->where('calling', $request->calling);
            $calling = $request->calling;
        }

        if ($request->no_answer != null) {
            $receipts = $receipts->where('no_answer', $request->no_answer);
            $no_answer = $request->no_answer;
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

        if ($request->delivery_man_id != null) {
            $receipts = $receipts->where('delivery_man_id', $request->delivery_man_id);
            $delivery_man_id = $request->delivery_man_id;
        }

        if ($request->description != null) {
            $description = $request->description;
            $receipts = $receipts->where('description', 'like', '%' . $request->description . '%');
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
            $receipts = $receipts->whereBetween('order_num', [ 'receipt-company#' . $from,  'receipt-company#' . $to]);
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
                $exclude2[] = 'receipt-company#' . $exc;
            }
            $receipts = $receipts->whereNotIn('order_num', $exclude2);
        }
        if ($request->include != null) {
            $include = $request->include;
            foreach(explode(',',$include) as $inc){
                $include2[] = 'receipt-company#' . $inc;
            }
            $receipts = $receipts->whereIn('order_num' ,$include2);
        }

        if ($request->has('download')) {
            return Excel::download(new ReceiptCompanyExport($receipts->get()), 'company_receipts_(' . $from_date . ')_(' . $to_date . ')_(' . $request->client_name . ').xlsx');
        } 
        if ($request->has('print')) {
            $receipts = $receipts->get(); 
            foreach($receipts as $receipt){
                $receipt->printing_times += 1;
                $receipt->save();
            }
            return view('admin.receiptCompanies.print', compact('receipts'));
        }
        
        $statistics = [
            'total_shipping_country_cost' => $receipts->sum('shipping_country_cost'),
            'total_deposit' => $receipts->sum('deposit'),
            'total_total_cost' => $receipts->sum('total_cost'),
        ];

        $receipts = $receipts->orderBy('quickly', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.receiptCompanies.index', compact(
            'countries', 'statistics','receipts','done','client_type','exclude',
            'delivery_status','payment_status','sent_to_delivery','calling','enable_multiple_form_submit',
            'country_id','no_answer','date_type','phone','client_name','order_num', 'deleted',
            'quickly','playlist_status','description', 'include','delivery_mans',
            'delivery_man_id','staff_id','from','to','from_date','to_date', 'staffs',  
        )); 
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('receipt_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $shipping_countries = Country::select('cost','name', 'id')->get();

        $previous_data = searchByPhone($request->phone_number);

        return view('admin.receiptCompanies.create', compact('shipping_countries','previous_data'));
    }

    public function store(StoreReceiptCompanyRequest $request)
    {
        $receiptCompany = ReceiptCompany::create($request->all());

        foreach ($request->input('photos', []) as $file) {
            $receiptCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $receiptCompany->id]);
        }

        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.receipt-companies.index');
    }

    public function edit(ReceiptCompany $receiptCompany)
    {
        abort_if(Gate::denies('receipt_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shipping_countries = Country::select('cost','name', 'id')->get();

        $receiptCompany->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country');

        $site_settings = get_site_setting(); 

        if($site_settings->delivery_system == 'wasla'){
            $waslaController = new WaslaController;
            $response = $waslaController->countries();
        }else{
            $response = '';
        }
        return view('admin.receiptCompanies.edit', compact('receiptCompany', 'shipping_countries','response','site_settings'));
    }

    public function update(UpdateReceiptCompanyRequest $request, ReceiptCompany $receiptCompany)
    {
        $receiptCompany->update($request->all());

        if (count($receiptCompany->photos) > 0) {
            foreach ($receiptCompany->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $receiptCompany->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $receiptCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        toast(trans('flash.global.update_title'),'success');
        return redirect()->route('admin.receipt-companies.index');
    }

    public function show(ReceiptCompany $receiptCompany)
    {
        abort_if(Gate::denies('receipt_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptCompany->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country');

        return view('admin.receiptCompanies.show', compact('receiptCompany'));
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('receipt_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptCompany = ReceiptCompany::withTrashed()->find($id); 
        if($receiptCompany->playlist_status != 'pending'){ 
            if(!auth()->user()->is_admin){ 
                alert('Cant delete','Contact Ur Adminstrator','warning'); 
                return 1;
            }
        }
        if($receiptCompany->deleted_at != null){
            $receiptCompany->forceDelete();
        }else{
            $receiptCompany->delete();
        } 

        alert(trans('flash.deleted'),'','success');

        return 1;
    } 

    public function restore($id)
    {
        abort_if(Gate::denies('receipt_company_restore'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptCompany = ReceiptCompany::withTrashed()->find($id);
        $receiptCompany->restore();

        alert(trans('flash.restored'),'','success');

        return redirect()->route('admin.receipt-companies.index');
    } 

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('receipt_company_create') && Gate::denies('receipt_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ReceiptCompany();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
