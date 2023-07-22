<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WaslaController;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Category;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response; 

class OrdersController extends Controller
{
    public function update_statuses(Request $request){ 
        $type = $request->type;
        $order = Order::findOrFail($request->id);
        $order->$type = $request->status; 
        $order->save();
        return 1;
    }

    public function print($id){
        $orders = Order::with('orderDetails','user')->whereIn('id',[$id])->get(); 
        foreach($orders as $order){
            $order->printing_times += 1;
            $order->save();
        }
        return view('admin.orders.print',compact('orders'));
    }

    public function update_delivery_man(Request $request){ 
        $order = Order::find($request->row_id);
        $order->delivery_man_id = $request->delivery_man_id;
        $order->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
        $order->delivery_status = 'on_delivery'; 
        $order->save();
        toast(trans('flash.global.success_title'),'success');
        return redirect()->route('admin.orders.show',$order->id);
    }

    public function send_to_wasla(Request $request){
        $order = Order::findOrFail($request->row_id);
        $company_id = Auth::user()->wasla_company_id;

        $order_products_pivot = OrderDetail::with('product')->where('order_id', $request->row_id)->orderBy('updated_at', 'desc')->get();

        $description = '';
        $note = '';
        foreach ($order_products_pivot as $raw) {
            $description .= $raw->product ? $raw->product->name : '';
            $description .= ' <br> ';

            $note .= $raw->description;
            $note .= ' <br> ';
        }

        $data = [
            //from receipt
            'company_id' => $company_id,
            'receiver_name' => $order->client_name,
            'phone_1' => $order->phone_number,
            'phone_2' => $order->phone_number_2,
            'address' => $order->shipping_address,
            'description' => $description,
            'note' => $note,
            'receipt_code' => $order->order_num,

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
                $order->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
                $order->delivery_status = 'on_delivery'; 
                $order->save();
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
    public function index(Request $request)
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); 

        $users = User::whereIn('user_type',['customer','seller'])->get();
        $delivery_mans = User::where('user_type', 'delivery_man')->get();
        $countries = Country::where('status',1)->get()->groupBy('type');  
        $websites = WebsiteSetting::pluck('site_name', 'id');
        
        $phone = null;
        $client_name = null;
        $order_num = null;
        $delivery_status = null;
        $payment_status = null;
        $payment_type = null;
        $user_id = null;
        $delivery_man_id = null;
        $from = null;
        $to = null;
        $calling = null;
        $quickly = null;
        $country_id = null;
        $commission_status = null;
        $playlist_status = null;
        $sent_to_wasla = null;
        $order_type = null;
        $sent_to_delivery = null;
        $exclude = null;
        $include = null; 
        $from_date = null;
        $to_date = null;
        $date_type = null;
        $description = null;
        $website_setting_id = null;


        $orders = Order::with(['orderDetails','shipping_country','user','delivery_man']);

        if ($request->order_type != null){
            $orders = $orders->where('order_type',$request->order_type);
            $order_type = $request->order_type;
        }

        if ($request->sent_to_wasla != null){
            $orders = $orders->where('sent_to_wasla',$request->sent_to_wasla);
            $sent_to_wasla = $request->sent_to_wasla;
        }
        if ($request->delivery_status != null) {
            $delivery_status = $request->delivery_status;
            $orders = $orders->where('delivery_status',$delivery_status);
        }

        if ($request->description != null) {
            $description = $request->description;
            $orders = $orders->whereHas('orderDetails', function ($query) use ($description) {
                $query->where('description', 'like', '%' . $description . '%');
            });
        }

        if ($request->sent_to_delivery != null) {
            $sent_to_delivery = $request->sent_to_delivery;
            if($sent_to_delivery){
                $orders = $orders->whereNotNull('send_to_delivery_date');
            }else{
                $orders = $orders->whereNull('send_to_delivery_date');
            }
        }
        
        if ($request->payment_status != null) {
            $payment_status = $request->payment_status;
            $orders = $orders->where('payment_status',$payment_status);
        }
        if ($request->payment_type != null) {
            $payment_type = $request->payment_type;
            $orders = $orders->where('payment_type',$payment_type);
        }
        if ($request->website_setting_id != null) {
            $website_setting_id = $request->website_setting_id;
            $orders = $orders->where('website_setting_id',$website_setting_id);
        }
        if ($request->playlist_status != null) {
            $playlist_status = $request->playlist_status;
            $orders = $orders->where('playlist_status',$playlist_status);
        }

        if ($request->country_id != null) {
            $country_id = $request->country_id;
            $orders = $orders->where('shipping_country_id',$country_id);
        }

        if ($request->commission_status != null) {
            $commission_status = $request->commission_status;
            $orders = $orders->where('commission_status',$commission_status);
        }


        if ($request->calling != null) {
            $calling = $request->calling;
            $orders = $orders->where('calling',$calling);
        }
        if ($request->quickly != null) {
            $quickly = $request->quickly;
            $orders = $orders->where('quickly',$quickly);
        }

        if ($request->client_name != null){
            $orders = $orders->where('client_name', 'like', '%'.$request->client_name.'%');
            $client_name = $request->client_name;
        }
        if ($request->order_num != null){
            $orders = $orders->where('order_num', 'like', '%'.$request->order_num.'%');
            $order_num = $request->order_num;
        }

        if ($request->user_id != null) {
            $user_id = $request->user_id;
            $orders = $orders->where('user_id',$request->user_id);
        }

        if ($request->delivery_man_id != null) {
            $delivery_man_id = $request->delivery_man_id;
            $orders = $orders->where('delivery_man_id',$request->delivery_man_id);
        }

        if ($request->phone != null){
            global $phone;
            $phone = $request->phone;
            $orders = $orders->where(function ($query) {
                                    $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                            ->orWhere('phone_number_2', 'like', '%'.$GLOBALS['phone'].'%');
                                });
        }

        if ($request->from != null && $request->to != null && $request->order_type) {
            $from = $request->from;
            $to = $request->to;
            $orders = $orders->whereBetween('order_num', [$request->order_type .'#' . $from, $request->order_type .'#' . $to]);
        }
        if ($request->from_date != null && $request->to_date != null && $request->date_type != null) {  
            $from_date = \Carbon\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $request->from_date . ' ' . '12:00 am')->format('Y-m-d H:i:s');
            $to_date = \Carbon\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $request->to_date . ' ' . '11:59 pm')->format('Y-m-d H:i:s'); 
            $date_type = $request->date_type;
            $orders = $orders->whereBetween($date_type, [$from_date, $to_date]);
        }
        
        if ($request->exclude != null) {
            $exclude = $request->exclude; 
            foreach(explode(',',$exclude) as $exc){
                $exclude2[] = $exc;
            }
            $orders = $orders->where(function ($query) use($exclude2) {
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
            $orders = $orders->where(function ($query) use($include2) {
                for ($i = 0; $i < count($include2); $i++){
                    $query->orwhere('order_num', 'like',  '%' . $include2[$i] .'%');
                }      
            });
        }

        if($request->has('download')){
            return Excel::download(new OrdersExport($orders->get()), 'orders.xlsx');
        } 

        if ($request->has('print')) {
            $orders = $orders->with('orderDetails.product')->get();
            foreach($orders as $order){
                $order->printing_times += 1;
                $order->save();
            }
            return view('admin.orders.print', compact('orders'));
        }

        $statistics = [
            'total_total_cost' => $orders->sum('total_cost') + $orders->sum('extra_commission'),
            'total_shipping_country_cost' => $orders->sum('shipping_country_cost'),
            'total_deposit' => $orders->sum('deposit_amount'),
            'total_commission' => $orders->sum('commission') + $orders->sum('extra_commission'),
        ];
        $orders = $orders->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.orders.index', compact('statistics','users','orders','country_id','quickly','delivery_man_id','website_setting_id','websites',
                                            'payment_status','delivery_status','calling', 'playlist_status','sent_to_delivery','date_type','payment_type',
                                            'client_name','phone' ,'order_num', 'countries','delivery_mans','exclude', 'include', 'from_date',
                                            'user_id','from' , 'to','commission_status','sent_to_wasla','order_type','to_date','description'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preparers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $delivery_men = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orders.create', compact('delivery_men', 'preparers', 'shipping_countries', 'users'));
    }

    public function store(StoreOrderRequest $request)
    {
        $order = Order::create($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $preparers = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $delivery_men = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $order->load('user', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipment', 'delivery_man');

        return view('admin.orders.edit', compact('delivery_men', 'order', 'preparers', 'shipping_countries', 'users'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all());

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('user','orderDetails.product', 'shipping_country', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man');

        $site_settings = get_site_setting(); 
        
        if($site_settings->delivery_system == 'wasla'){
            $waslaController = new WaslaController;
            $response = $waslaController->countries();
        }else{
            $response = '';
        }
        return view('admin.orders.show', compact('order','site_settings','response'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->delete();

        return 1;
    }

    public function show_order_detail(Request $request){
        $orderDetail = OrderDetail::findOrFail($request->id);
        $orderDetail->load(['product','order']);
        return view('admin.orders.partials.show_details', compact('orderDetail'));
    }
    public function update_order_detail(Request $request){
        $orderDetail = OrderDetail::findOrFail($request->id);
        $orderDetail->extra_commission = $request->extra_commission;
        
        if($orderDetail->save()){
            $order = Order::with('orderDetails')->find($orderDetail->order_id);
            $extra_commission = 0;
            foreach($order->orderDetails as $raw){
                $extra_commission = $extra_commission + $raw->extra_commission;
            }
            $order->extra_commission = $extra_commission;
            $order->save();
        }else{
            return 0;
        }
        
        toast(trans('flash.global.update_title'),'success'); 
        return redirect()->route('admin.orders.show',$orderDetail->order_id);
    } 

    public function destroy_product($id){
        $orderDetail = OrderDetail::findOrFail($id);

        $order = Order::find($orderDetail->order_id);

        if($order->playlist_status != 'pending'){
            alert(trans('flash.cant_delete'),'','error');
            return 1;
        }  

        $order->commission = $order->commission - $orderDetail->commission;
        $order->extra_commission = $order->extra_commission - $orderDetail->extra_commission;
        $order->total_cost = $order->total_cost - $orderDetail->total_cost;
        $order->save();
        
        $orderDetail->delete();
        alert(trans('flash.deleted'),'','success');
        return 1;
    } 
}
