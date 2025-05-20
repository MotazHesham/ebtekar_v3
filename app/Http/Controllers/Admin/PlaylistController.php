<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotificationController; 
use App\Jobs\SendPushNotification; 
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
use App\Support\Collection; 
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PlaylistController extends Controller
{
    public function getCounters(){
        
        $playlists_counter = ViewPlaylistData::select('playlist_status', DB::raw('COUNT(*) as count'))
            ->whereIn('playlist_status', ['design', 'manufacturing', 'prepare', 'shipment'])
            ->groupBy('playlist_status')
            ->pluck('count', 'playlist_status')
            ->toArray();

        $playlists_counter = array_merge([
            'design' => 0,
            'manufacturing' => 0,
            'prepare' => 0,
            'shipment' => 0,
        ], $playlists_counter);

        $playlists_counter_sum = 
            (Gate::allows('playlist_design') ? $playlists_counter['design'] : 0) +
            (Gate::allows('playlist_manufacturing') ? $playlists_counter['manufacturing'] : 0) +
            (Gate::allows('playlist_prepare') ? $playlists_counter['prepare'] : 0) +
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
        return view('partials.playlist_users',compact('raw','staffs','site_settings','id','model_type'));
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
        
        $old_status = $raw->playlist_status;

        if ($old_status == 'pending') {
            $raw->send_to_playlist_date = date(config('panel.date_format') . ' ' . config('panel.time_format')); 
            if($raw->website_setting_id == 5 || $raw->website_setting_id == 4){
                $raw->playlist_status = 'prepare';
            }else{
                $raw->playlist_status = 'design'; 
            }
        }

        $raw->designer_id = $request->designer_id;
        $raw->manufacturer_id = $request->manufacturer_id;
        $raw->preparer_id = $request->preparer_id;
        $raw->shipmenter_id = $request->shipmenter_id;
        $raw->save();

        if ($old_status == 'pending') {
            $body = 'فاتورة جديدة';   
            $userAlert = UserAlert::create([
                'alert_text' => $raw->order_num . ' ' . $body,
                'alert_link' => route('admin.playlists.index', 'design'),
                'data' => $raw->id . '&' . $request->model_type,
                'type' => 'private', 
            ]);
            $userAlert->users()->sync([$request->designer_id]);

            $user = User::find($request->designer_id);
            if ($user->device_token != null) {
                $tokens = array();
                array_push($tokens, $user->device_token); 
                $site_settings = get_site_setting();
                SendPushNotification::dispatch($raw->order_num, $body, $tokens,route('admin.playlists.index', 'design'),$site_settings);  // job for sending push notification
            }
            alert('تم الأرسال','','success');
            return redirect()->route($route);
        }else{
            alert('تم التعديل','','success');
            return redirect()->route($route);
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

        if($raw->printing_times == 0 && $raw->playlist_status == 'design' && $request->condition == 'send'){  
            return 0; // should printing the receipt first
        } 

        $raw->send_to_playlist_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $raw->playlist_status = $request->status;
        $raw->save();  

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
        
        $type = $request->type;  
        $playlists = ViewPlaylistData::orderBy('send_to_playlist_date','desc')->where('playlist_status',$type); 
        $websites = WebsiteSetting::pluck('site_name', 'id');
        
        $order_num = null;
        $user_id = null;
        $website_setting_id = null;
        $description = null;
        $to_date = null;
        $quickly = null;
        $client_review = null;
        $is_seasoned = null;
        $client_type = null;
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
        return view('admin.playlists.index',compact('dates','playlists','view','staffs','client_review','type', 'order_num','user_id','is_seasoned','quickly','website_setting_id','description','to_date','websites','client_type'));

    } 
}
