<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotificationController; 
use App\Jobs\SendPushNotification;
use App\Jobs\SendReceiptToEgyptExpressJob;
use App\DTOs\Factories\EgyptExpressAirwayBillDTOFactory;
use App\Models\EgyptExpressAirwayBill;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Printable;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\User;
use App\Models\UserAlert;
use App\Models\ViewPlaylistData;
use App\Models\WebsiteSetting;
use App\Models\Zone;
use App\Models\PlaylistHistory;
use App\Support\Collection; 
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PlaylistController extends Controller
{
    public function getCounters(){
        
        $playlists_counter = ViewPlaylistData::select('playlist_status', DB::raw('COUNT(*) as count'))
            ->whereIn('playlist_status', ['design', 'manufacturing', 'prepare', 'review', 'shipment'])
            ->groupBy('playlist_status')
            ->pluck('count', 'playlist_status')
            ->toArray();

        $playlists_counter = array_merge([
            'design' => 0,
            'manufacturing' => 0,
            'prepare' => 0,
            'review' => 0,
            'shipment' => 0,
        ], $playlists_counter);

        $playlists_counter_sum = 
            (Gate::allows('playlist_design') ? $playlists_counter['design'] : 0) +
            (Gate::allows('playlist_manufacturing') ? $playlists_counter['manufacturing'] : 0) +
            (Gate::allows('playlist_prepare') ? $playlists_counter['prepare'] : 0) +
            (Gate::allows('playlist_review') ? $playlists_counter['review'] : 0) +
            (Gate::allows('playlist_shipment') ? $playlists_counter['shipment'] : 0);

        $playlists_counter['total'] = $playlists_counter_sum;

        return response()->json($playlists_counter,200);
    }
    public function client_review($id , $model_type){   
        if($model_type == 'social'){
            $raw = ReceiptSocial::find($id); 
        }elseif($model_type == 'company'){
            $raw = ReceiptCompany::find($id); 
        }elseif($model_type == 'order'){
            $raw = Order::find($id); 
        }  
        $raw->client_review = $raw->client_review ? 0 : 1;
        $raw->save();
        toast('Success .....','success');
        return redirect()->back();
    }

    public function playlist_users(Request $request){   
        if($request->model_type == 'social'){
            $raw = ReceiptSocial::find($request->id); 
        }elseif($request->model_type == 'company'){
            $raw = ReceiptCompany::find($request->id); 
        }elseif($request->model_type == 'order'){
            $raw = Order::find($request->id); 
        } 
        $id = $request->id;
        $model_type = $request->model_type;
        $staffs = User::whereIn('user_type', ['staff', 'admin'])->where('email', '!=', 'wezaa@gmail.com')->get();
        $site_settings = WebsiteSetting::where('id',$raw->website_setting_id)->first();
        if(!$site_settings){
            $site_settings = WebsiteSetting::where('id',1)->first();
        }
        $histories = PlaylistHistory::with(['user', 'assignedToUser'])
            ->where('model_type', $request->model_type)
            ->where('model_id', $request->id)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('partials.playlist_users',compact('raw','staffs','site_settings','id','model_type','histories'));
    }

    public function update_playlist_users(Request $request)
    {
        if($request->model_type == 'social'){
            $raw = ReceiptSocial::find($request->id); 
            $route = 'admin.receipt-socials.index';
        }elseif($request->model_type == 'company'){
            $raw = ReceiptCompany::find($request->id); 
            $route = 'admin.receipt-companies.index';
        }elseif($request->model_type == 'order'){
            $raw = Order::find($request->id); 
            $route = 'admin.orders.index';
        } 
        
        $website_setting = WebsiteSetting::find($raw->website_setting_id);
        
        $old_status = $raw->playlist_status;

        if ($old_status == 'pending') {
            $raw->send_to_playlist_date = date(config('panel.date_format') . ' ' . config('panel.time_format')); 
            $raw->playlist_status = $website_setting->playlist_status ? $website_setting->playlist_status : 'design';
        }

        if($request->model_type == 'social' && $raw->hold_in_playlist_status == 'design' ){
            $raw->hold = 1;
        }
        
        // Track assignment changes
        if($request->designer_id && $request->designer_id != $raw->designer_id){
            PlaylistHistory::create([
                'model_type' => $request->model_type,
                'model_id' => $raw->id,
                'action_type' => 'assignment',
                'from_status' => $raw->playlist_status,
                'to_status' => $raw->playlist_status,
                'is_return' => false,
                'reason' => null,
                'user_id' => Auth::id(),
                'assigned_to_user_id' => $request->designer_id,
                'assignment_type' => 'designer',
            ]);
        }
        if($request->manufacturer_id && $request->manufacturer_id != $raw->manufacturer_id){
            PlaylistHistory::create([
                'model_type' => $request->model_type,
                'model_id' => $raw->id,
                'action_type' => 'assignment',
                'from_status' => $raw->playlist_status,
                'to_status' => $raw->playlist_status,
                'is_return' => false,
                'reason' => null,
                'user_id' => Auth::id(),
                'assigned_to_user_id' => $request->manufacturer_id,
                'assignment_type' => 'manufacturer',
            ]);
        }
        if($request->preparer_id && $request->preparer_id != $raw->preparer_id){
            PlaylistHistory::create([
                'model_type' => $request->model_type,
                'model_id' => $raw->id,
                'action_type' => 'assignment',
                'from_status' => $raw->playlist_status,
                'to_status' => $raw->playlist_status,
                'is_return' => false,
                'reason' => null,
                'user_id' => Auth::id(),
                'assigned_to_user_id' => $request->preparer_id,
                'assignment_type' => 'preparer',
            ]);
        }
        if($request->shipmenter_id && $request->shipmenter_id != $raw->shipmenter_id){
            PlaylistHistory::create([
                'model_type' => $request->model_type,
                'model_id' => $raw->id,
                'action_type' => 'assignment',
                'from_status' => $raw->playlist_status,
                'to_status' => $raw->playlist_status,
                'is_return' => false,
                'reason' => null,
                'user_id' => Auth::id(),
                'assigned_to_user_id' => $request->shipmenter_id,
                'assignment_type' => 'shipmenter',
            ]);
        }
        
        $raw->designer_id = $request->designer_id ?? null;
        $raw->manufacturer_id = $request->manufacturer_id ?? null;
        $raw->preparer_id = $request->preparer_id ?? null;
        $raw->shipmenter_id = $request->shipmenter_id ?? null; 
        $raw->returned_to_design += 1;  // increment the returned to design count
        if($request->model_type == 'social' && $old_status == 'pending'){
            $raw->quickly_return = 0;
        }
        $raw->save();

        // Create airway bill if it doesn't exist and shipmenter is assigned
        if ($old_status == 'pending' && $raw->playlist_status == 'design' && $website_setting->shipping_integration) {
            $this->createAirwayBill($raw, $request->model_type);
        }

        if ($old_status == 'pending') {
            // store history of playlist flow
            PlaylistHistory::create([
                'model_type'  => $request->model_type,
                'model_id'    => $raw->id,
                'action_type' => 'status_change',
                'from_status' => $old_status,
                'to_status'   => 'design',
                'is_return'   => false,
                'reason'      => null,
                'user_id'     => Auth::id(),
            ]);
            $body = 'فاتورة جديدة';   
            $userAlert = UserAlert::create([
                'alert_text' => $raw->order_num . ' ' . $body,
                'alert_link' => route('admin.playlists.index', 'design'),
                'data' => $raw->id . '&' . $request->model_type,
                'type' => 'private', 
            ]);
            if($request->designer_id){
                $userAlert->users()->sync([$request->designer_id]);
                $user = User::find($request->designer_id);
                if ($user->device_token != null) {
                    $tokens = array();
                    array_push($tokens, $user->device_token); 
                    $site_settings = get_site_setting();
                    SendPushNotification::dispatch($raw->order_num, $body, $tokens,route('admin.playlists.index', 'design'),$site_settings);  // job for sending push notification
                }
            }

            alert('تم الأرسال','','success');
            return redirect()->back();
        }else{
            alert('تم التعديل','','success');
            return redirect()->back();
        }
    }

    public function update_playlist_status(Request $request)
    {
        if($request->model_type == 'social'){
            $raw = ReceiptSocial::find($request->id);
            $route = route('admin.receipt-socials.index'); 
        }elseif($request->model_type == 'company'){
            $raw = ReceiptCompany::find($request->id);
            $route = route('admin.receipt-companies.index'); 
        }elseif($request->model_type == 'order'){
            $raw = Order::find($request->id);
            $route = route('admin.orders.index'); 
        }  

        $old_status = $raw->playlist_status;

        if($raw->printing_times == 0 && $raw->playlist_status == 'design' && $request->condition == 'send'){  
            return 0; // should printing the receipt first
        } 

        $raw->send_to_playlist_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $raw->playlist_status = $request->status;
        if($request->status == 'design' && $request->model_type == 'social'){ 
            $raw->returned_to_design += 1; 
        }
        if($request->model_type == 'social' && $raw->hold_in_playlist_status == $request->status ){
            $raw->hold = 1;
        }
        if($request->model_type == 'social' && $request->status == 'manufacturing'){
            $raw->client_review = 0;
        }
        $raw->save();  

        // store history of playlist flow
        PlaylistHistory::create([
            'model_type'  => $request->model_type,
            'model_id'    => $raw->id,
            'action_type' => 'status_change',
            'from_status' => $old_status,
            'to_status'   => $request->status,
            'is_return'   => $request->condition == 'back',
            'reason'      => $request->reason ?? null,
            'user_id'     => Auth::id(),
        ]);

        if($request->condition == 'back' && $request->status == 'pending' && $request->model_type == 'social'){
            $raw->quickly_return = 1;
            $raw->save();
        }

        if($request->condition == 'send' && $request->status == 'design' && $request->model_type == 'social'){
            $raw->quickly_return = 0;
            $raw->save();
        }

        $auth_id = 0;
        $to_playlist = '';  
        if($raw->playlist_status == 'design'){ 
            $auth_id = $request->designer_id;
            $to_playlist = 'الي الديزاينر';
        }elseif($raw->playlist_status == 'manufacturing'){
            $auth_id = $request->manufacturer_id;
            $to_playlist = 'الي التصنيع';
        }elseif($raw->playlist_status == 'prepare'){
            $auth_id = $request->preparer_id;
            $to_playlist = 'الي التجهيز';
        }elseif($raw->playlist_status == 'shipment'){
            $auth_id = $request->shipmenter_id;
            $to_playlist = 'الي الأرسال للشحن';
        }elseif($raw->playlist_status == 'finish'){
            $to_playlist = 'الي الشحن';
        }elseif($raw->playlist_status == 'pending'){
            $to_playlist = 'الي الشركة';
        }


        $site_settings = get_site_setting();
        // sending to the playlist users notification
        if($auth_id != 0){
            if($request->condition == 'send'){
                $body = 'فاتورة جديدة';
            }else{
                $body = 'تم أرجاع الفاتورة';
            }
            $userAlert = UserAlert::create([
                'alert_text' => $raw->order_num . ' ' . $body,
                'alert_link' => route('admin.playlists.index',$raw->playlist_status),
                'data' => $raw->id . '&' . $request->model_type,
                'type' => 'private', 
            ]);
            $userAlert->users()->sync([$auth_id]);

            $user = User::find($auth_id);
            
            if($user->device_token != null){
                $tokens = array();
                array_push($tokens,$user->device_token); 
                SendPushNotification::dispatch($raw->order_num, $body, $tokens,route('admin.playlists.index',$raw->playlist_status),$site_settings);  // job for sending push notification
            }
        }
        // --------------------------------------------
    
        // save the playlist transfers to track in  the admin panel
        if($request->condition == 'send'){
            $body_2 = 'تم تحويل الفاتورة ' . $to_playlist;
        }else{
            $body_2 = 'تم أرجاع الفاتورة '. $to_playlist;
        }
        UserAlert::create([
            'alert_text' => $raw->order_num . ' ' . $body_2 . ' عن طريق ' . auth()->user()->name ,
            'alert_link' => $route,
            'data' => $raw->id . '&' . $request->model_type,
            'type' => 'playlist',
        ]);  
        $tokens = User::whereNotNull('device_token')->whereHas('roles.permissions',function($query){
            $query->where('permissions.title','playlist_show');
        })->where('user_type','staff')->pluck('device_token')->all(); // get the tokens to send via fcm firebase 
        SendPushNotification::dispatch($raw->order_num, $body_2, $tokens,$route,$site_settings);  // job for sending push notification
        // ------------------------------------------


        // assign the transfer to the user to be in his history list
        $userAlert_2 = UserAlert::create([
            'alert_text' => $raw->order_num . ' ' . $body_2,
            'alert_link' => $route,
            'data' => $raw->id . '&' . $request->model_type,
            'type' => 'history', 
        ]);
        $userAlert_2->users()->sync([Auth::id()]);
        return 1;
    }

    public function history(Request $request)
    {
        $histories = PlaylistHistory::with(['user', 'assignedToUser'])
            ->where('model_type', $request->model_type)
            ->where('model_id', $request->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.playlists.history', compact('histories'));
    }

    public function show_details(Request $request){

        $playlist = ViewPlaylistData::where('model_type',$request->model_type)->where('id',$request->id)->first();
        $raw = null;
        if($request->model_type == 'social'){
            $raw = ReceiptSocial::find($request->id);
            $raw->load('receiptsReceiptSocialProducts.products');
        }elseif($request->model_type == 'company'){
            $raw = ReceiptCompany::find($request->id);
        }elseif($request->model_type == 'order'){
            $raw = Order::find($request->id);
            $raw->load('orderDetails.product');
        }
        return view('admin.playlists.photos',compact('raw','playlist'));
    }

    public function required_items(Request $request){
        $type = $request->type;
        $items = ReceiptSocialProductPivot::whereHas('receipt',function($q) use ($type){
            $q->where('playlist_status',$type);
        })->selectRaw('title, sum(quantity) as quantity')->groupBy('title')->get();
        $items2 = OrderDetail::whereHas('order',function($q) use ($type){
            $q->where('playlist_status',$type);
        })->selectRaw('product_id, sum(quantity) as quantity')->groupBy('product_id')->get();

        return view('admin.playlists.required_items',compact('items','items2'));
    }

    public function print($id,$model_type){
        
        if($model_type == 'social'){
            $raw = ReceiptSocial::find($id); 
            $printable_model = 'App\Models\ReceiptSocial';
            $print_route = 'admin.receipt-socials.print';
        }elseif($model_type == 'company'){
            $raw = ReceiptCompany::find($id);
            $printable_model = 'App\Models\ReceiptCompany'; 
            $print_route = 'admin.receipt-companies.print';
        }elseif($model_type == 'order'){
            $raw = Order::find($id); 
            $printable_model = 'App\Models\Order'; 
            $print_route = 'admin.orders.print';
        }
        $printed = Printable::where('user_id',Auth::id())->where('printable_id',$id)->where('printable_model',$printable_model)->first();
        if($printed && $raw->printing_times > 0){ 
            // alert('تم الطباعة من قبل','','error');
            return 0;
        }else{
            if(!auth()->user()->is_admin){
                Printable::create([
                    'user_id' => Auth::id(),
                    'printable_id' => $raw->id,
                    'printable_model' => $printable_model
                ]);
            }
            return redirect()->route($print_route,$id);
        } 
    }
    public function check_printable(Request $request){
        
        if($request->model_type == 'social'){ 
            $printable_model = 'App\Models\ReceiptSocial'; 
        }elseif($request->model_type == 'company'){ 
            $printable_model = 'App\Models\ReceiptCompany';  
        }elseif($request->model_type == 'order'){ 
            $printable_model = 'App\Models\Order';  
        }
        $printed = Printable::where('user_id',Auth::id())->where('printable_id',$request->id)->where('printable_model',$printable_model)->first();
        if($printed){  
            return 1;
        }else{ 
            return 0;
        } 
    }
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('playlist_'.$request->type), Response::HTTP_FORBIDDEN, '403 Forbidden');  

        $staffs = User::whereIn('user_type',['staff','seller'])->get();

        $staffs_array = $staffs->pluck('name', 'id')->toArray();
        
        $zones = Zone::all();
        $type = $request->type;  
        $playlists = ViewPlaylistData::orderBy('client_review','desc')->orderBy('send_to_playlist_date','desc')->where('playlist_status',$type); 
        $websites = WebsiteSetting::pluck('site_name', 'id');

        if(!auth()->user()->is_admin){
            if($type == 'design'){
                $playlists = $playlists->where(function($query){
                    $query->whereNull('designer_id')->orWhere('designer_id',auth()->user()->id);
                });
            } 
        }
        
        $order_num = null;
        $user_id = null;
        $website_setting_id = null;
        $description = null;
        $to_date = null;
        $quickly = null;
        $client_review = null;
        $is_seasoned = null;
        $client_type = null;
        $zone_id = null;
        $designer_id = null;
        $manufacturer_id = null;
        $preparer_id = null;
        $reviewer_id = null;
        $shipmenter_id = null;
        $view = 'all';

        if( $request->view != null){
            $view = $request->view;
        }

        if( $request->to_date != null){
            $to_date = strtotime($request->to_date);
            $playlists = $playlists->whereDate('send_to_playlist_date',date('Y-m-d',$to_date).' 00:00:00'); 
        }
        if( $request->user_id != null){
            $user_id = $request->user_id;
            $playlists = $playlists->where('user_id',$request->user_id); 
        }
        if( $request->client_type != null){
            $client_type = $request->client_type;
            $playlists = $playlists->where('client_type',$request->client_type); 
        }
        if( $request->quickly != null){
            $quickly = $request->quickly;
            $playlists = $playlists->where('quickly',$request->quickly); 
        }
        if( $request->is_seasoned != null){
            $is_seasoned = $request->is_seasoned;
            $playlists = $playlists->where('is_seasoned',$request->is_seasoned); 
        }
        if( $request->client_review != null){
            $client_review = $request->client_review;
            $playlists = $playlists->where('client_review',$request->client_review); 
        }
        if( $request->website_setting_id != null){
            $website_setting_id = $request->website_setting_id;
            $playlists = $playlists->where('website_setting_id',$request->website_setting_id); 
        }
        if( $request->zone_id != null){
            $zone_id = $request->zone_id;
            $zone = Zone::find($request->zone_id);
            $countryIds = $zone->countries->pluck('id')->toArray();
            $playlists = $playlists->whereIn('shipping_country_id',$countryIds); 
        }
        if( $request->designer_id != null){
            $designer_id = $request->designer_id;
            $playlists = $playlists->where('designer_id',$request->designer_id); 
        }
        if( $request->manufacturer_id != null){
            $manufacturer_id = $request->manufacturer_id;
            $playlists = $playlists->where('manufacturer_id',$request->manufacturer_id); 
        }
        if( $request->preparer_id != null){
            $preparer_id = $request->preparer_id;
            $playlists = $playlists->where('preparer_id',$request->preparer_id); 
        }
        if( $request->shipmenter_id != null){
            $shipmenter_id = $request->shipmenter_id;
            $playlists = $playlists->where('shipmenter_id',$request->shipmenter_id); 
        }
        if( $request->reviewer_id != null){
            $reviewer_id = $request->reviewer_id;
            $playlists = $playlists->where('reviewer_id',$request->reviewer_id); 
        }
        if ($request->order_num != null){
            $order_num = $request->order_num;
            $playlists = $playlists->where('order_num', 'like', '%'.$request->order_num.'%'); 
        }

        if ($request->description != null){
            $description = $request->description;
            $playlists = $playlists->where('description', 'like', '%'.$request->description.'%');
        } 

        if($view == 'by_date'){  
            $dates =(new Collection(collect($playlists->get())))->groupBy(function($plalylist) {
                return Carbon::createFromFormat(config('panel.date_format'), explode(" ",$plalylist->send_to_playlist_date)[0])->format('Y-m-d');
            })->paginate(6);
            $playlists = null;
        }else{
            $playlists = $playlists->paginate(15);
            $dates = null;
        } 
        // return $dates; 
        return view('admin.playlists.index',compact('dates','playlists','view','staffs','client_review','type', 'order_num','user_id','is_seasoned','quickly','website_setting_id',
        'description','to_date','websites','client_type','zones','zone_id','designer_id','manufacturer_id','preparer_id','shipmenter_id','reviewer_id','staffs_array'));

    }

    /** 
     *
     * @param mixed $model (ReceiptSocial, ReceiptCompany, or Order)
     * @param string $modelType
     * @return void
     */
    protected function createAirwayBill($model, $modelType)
    {
        // Determine the model class name
        $modelClass = get_class($model);
        
        // Check if airway bill already exists for this model
        $existingAirwayBill = EgyptExpressAirwayBill::where('model_type', $modelClass)
            ->where('model_id', $model->id)
            ->where('is_successful', true)
            ->first();

        // if ($existingAirwayBill) {
        //     // Airway bill already exists and was successful
        //     return;
        // }

        try {
            // Create DTO based on model type
            $dto = null;
            if ($modelType == 'social' && $model instanceof ReceiptSocial) {
                $dto = EgyptExpressAirwayBillDTOFactory::fromReceiptSocial($model);
            } elseif ($modelType == 'company' && $model instanceof ReceiptCompany) {
                $dto = EgyptExpressAirwayBillDTOFactory::fromReceiptCompany($model);
            } elseif ($modelType == 'order' && $model instanceof Order) {
                $dto = EgyptExpressAirwayBillDTOFactory::fromOrder($model);
            }

            // Dispatch job to create airway bill if DTO was created
            if ($dto && $dto->isValid()) {
                SendReceiptToEgyptExpressJob::dispatch($dto);
            }
        } catch (\Exception $e) {
            // Log error but don't break the flow
            Log::error('Failed to create airway bill in PlaylistController', [
                'model_type' => $modelType,
                'model_id' => $model->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getAirwayBillPdf(Request $request, $id, $modelType)
    {
        // Map model type to model class
        $modelClassMap = [
            'social' => ReceiptSocial::class,
            'company' => ReceiptCompany::class,
            'order' => Order::class,
        ];

        if (!isset($modelClassMap[$modelType])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid model type'
            ], 400);
        }

        $modelClass = $modelClassMap[$modelType];
        $model = $modelClass::findOrFail($id);
        
        // Find the airway bill for this model
        $airwayBill = EgyptExpressAirwayBill::where('model_type', $modelClass)
            ->where('model_id', $model->id)
            ->where('is_successful', true)
            ->whereNotNull('airwaybillpdf')
            ->latest()
            ->first();

        if (!$airwayBill || !$airwayBill->airwaybillpdf) {
            return response()->json([
                'success' => false,
                'message' => 'Airway bill PDF not found for this order'
            ], 404);
        }

        $pdfPath = $airwayBill->airwaybillpdf;
        
        // Check if file exists
        if (!file_exists($pdfPath)) {
            return response()->json([
                'success' => false,
                'message' => 'PDF file not found on server'
            ], 404);
        }

        // Return the PDF file for download
        if ($request->has('download')) {
            return response()->download($pdfPath, 'airwaybill-' . ($airwayBill->airway_bill_number ?? $model->id) . '.pdf');
        }

        // Return PDF for viewing/printing
        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function assign_to_me(Request $request)
    {
        $model = null;
        if($request->model_type == 'social'){
            $model = ReceiptSocial::find($request->id);
        }elseif($request->model_type == 'company'){
            $model = ReceiptCompany::find($request->id);
        }elseif($request->model_type == 'order'){
            $model = Order::find($request->id);
        }

        $assignmentType = null;
        $oldAssignedUserId = null;

        if($request->type == 'design'){
            if($model->designer_id){
                return response()->json(['success' => false, 'message' => 'الفاتورة معينة مسبقا لديزاينر آخر'], 400);
            }
            $oldAssignedUserId = $model->designer_id;
            $model->designer_id = Auth::id();
            $assignmentType = 'designer';
        }elseif($request->type == 'manufacturing'){
            if($model->manufacturer_id){
                return response()->json(['success' => false, 'message' => 'الفاتورة معينة مسبقا لمصنع آخر'], 400);
            }
            $oldAssignedUserId = $model->manufacturer_id;
            $model->manufacturer_id = Auth::id();
            $assignmentType = 'manufacturer';
        }elseif($request->type == 'prepare'){
            if($model->preparer_id){
                return response()->json(['success' => false, 'message' => 'الفاتورة معينة مسبقا لمجهز آخر'], 400);
            }
            $oldAssignedUserId = $model->preparer_id;
            $model->preparer_id = Auth::id();
            $assignmentType = 'preparer';
        } elseif($request->type == 'review'){
            if($model->reviewer_id){
                return response()->json(['success' => false, 'message' => 'الفاتورة معينة مسبقا لمراجع آخر'], 400);
            }
            $oldAssignedUserId = $model->reviewer_id;
            $model->reviewer_id = Auth::id();
            $assignmentType = 'reviewer';
        } elseif($request->type == 'shipment'){
            if($model->shipmenter_id){
                return response()->json(['success' => false, 'message' => 'الفاتورة معينة مسبقا لشاحن آخر'], 400);
            }
            $oldAssignedUserId = $model->shipmenter_id;
            $model->shipmenter_id = Auth::id();
            $assignmentType = 'shipmenter';
        }
        $model->save();

        // Store assignment history
        PlaylistHistory::create([
            'model_type' => $request->model_type,
            'model_id' => $model->id,
            'action_type' => 'assignment',
            'from_status' => $model->playlist_status,
            'to_status' => $model->playlist_status, // Status doesn't change on assignment
            'is_return' => false,
            'reason' => null,
            'user_id' => Auth::id(), // Who made the assignment
            'assigned_to_user_id' => Auth::id(), // Who was assigned (self-assignment)
            'assignment_type' => $assignmentType,
        ]);

        return response()->json(['success' => true, 'message' => 'تم التعيين بنجاح'], 200);
    }
}
