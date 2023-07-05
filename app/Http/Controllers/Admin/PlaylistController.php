<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotificationController;
use App\Http\Requests\MassDestroyPlaylistRequest;
use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest; 
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\User;
use App\Models\UserAlert;
use App\Models\ViewPlaylistData;
use App\Models\WebsiteSetting;
use App\Support\Collection;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; 
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

class PlaylistController extends Controller
{

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
        $site_settings = get_site_setting();
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
            $raw->playlist_status = 'design'; 
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
                'type' => 'private', 
            ]);
            $userAlert->users()->sync([$request->designer_id]);

            $user = User::find($request->designer_id);
            if ($user->device_token != null) {
                $tokens = array();
                array_push($tokens, $user->device_token);
                $push_controller = new PushNotificationController();
                // $push_controller->sendNotification($raw->order_num, $body, $tokens, route('admin.playlists.index', 'design'));
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
                'type' => 'private', 
            ]);
            $userAlert->users()->sync([$auth_id]);

            $user = User::find($auth_id);
            
            if($user->device_token != null){
                $tokens = array();
                array_push($tokens,$user->device_token);
                $push_controller = new PushNotificationController();
                // $push_controller->sendNotification($raw->order_num, $body, $tokens,route('admin.playlists.index',$raw->playlist_status));
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
            'alert_text' => $raw->order_num . ' ' . $body_2,
            'alert_link' => $route,
            'type' => 'playlist',
        ]);  
        $tokens = User::whereNotNull('device_token')->whereIn('user_type',['staff','admin'])->pluck('device_token')->all(); // get the tokens to send via fcm firebase
        $push_controller = new PushNotificationController();
        // $push_controller->sendNotification($raw->order_num, $body_2, $tokens,$route);
        // ------------------------------------------


        // assign the transfer to the user to be in his history list
        $userAlert_2 = UserAlert::create([
            'alert_text' => $raw->order_num . ' ' . $body_2,
            'alert_link' => $route,
            'type' => 'history', 
        ]);
        $userAlert_2->users()->sync([Auth::id()]);
        return 1;
    }

    public function show_details(Request $request){

        if($request->model_type == 'social'){
            $raw = ReceiptSocial::find($request->id);
        }elseif($request->model_type == 'company'){
            $raw = ReceiptCompany::find($request->id);
        }elseif($request->model_type == 'order'){
            $raw = Order::find($request->id);
        }
        $model_type = $request->model_type;
        return view('admin.playlist.photos',compact('resource'));
    }

    public function print($order_num){
        return $order_num;
    }
    
    public function index(Request $request)
    {
        abort_if(Gate::denies('playlist_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');  

        $staffs = User::whereIn('user_type',['staff','seller'])->get();
        
        $type = $request->type; 
        
        $playlists = ViewPlaylistData::orderBy('send_to_playlist_date','desc')->where('playlist_status',$type); 
        

        $order_num = null;
        $user_id = null;
        $description = null;
        $to_date = null;
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
        return view('admin.playlists.index',compact('dates','playlists','view','staffs','type', 'order_num','user_id','description','to_date'));

    }

    public function create()
    {
        abort_if(Gate::denies('playlist_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.create');
    }

    public function store(StorePlaylistRequest $request)
    {
        $playlist = Playlist::create($request->all());

        return redirect()->route('admin.playlists.index');
    }

    public function edit(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.edit', compact('playlist'));
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist)
    {
        $playlist->update($request->all());

        return redirect()->route('admin.playlists.index');
    }

    public function show(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.playlists.show', compact('playlist'));
    }

    public function destroy(Playlist $playlist)
    {
        abort_if(Gate::denies('playlist_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $playlist->delete();

        return back();
    }

    public function massDestroy(MassDestroyPlaylistRequest $request)
    {
        $playlists = Playlist::find(request('ids'));

        foreach ($playlists as $playlist) {
            $playlist->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
