<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BannedPhone;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\RBranch;
use App\Models\RClient;
use App\Models\ReceiptBranch;
use App\Models\ReceiptBranchProduct;
use App\Models\ReceiptBranchProductPivot;
use App\Models\ReceiptClient;
use App\Models\ReceiptClientProduct;
use App\Models\ReceiptClientProductPivot;
use App\Models\ReceiptCompany;
use App\Models\ReceiptSocial;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\ViewPlaylistData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class HomeController extends Controller
{    

    public function generateSiteMap(){ 
        $site_settings = get_site_setting();

        $sitemap = Sitemap::create();

        $sitemap->add(Url::create('/'));
        $sitemap->add(Url::create('/login'));

        $categories = Category::where('website_setting_id',$site_settings->id)->where('published',1)->get();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create("/search?category={$category->slug}")
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        }

        $subCategories = SubCategory::where('website_setting_id',$site_settings->id)->where('published',1)->get();
        foreach ($subCategories as $subCategory) {
            $sitemap->add(
                Url::create("/search?sub_category={$subCategory->slug}")
                    ->setLastModificationDate($subCategory->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        }
        $subSubCategories = SubSubCategory::where('website_setting_id',$site_settings->id)->where('published',1)->get();
        foreach ($subSubCategories as $subSubCategory) {
            $sitemap->add(
                Url::create("/search?sub_sub_category={$subSubCategory->slug}")
                    ->setLastModificationDate($subSubCategory->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        }

        $products = Product::where('website_setting_id',$site_settings->id)->where('published',1)->get();
        foreach ($products as $product) {
            $sitemap->add(
                Url::create("/product/{$product->slug}")
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(0.9)
            );
        }

        $sitemap->writeToFile(public_path($site_settings->sitemap_link_seo));

        return 'Sitemap Generated To ' . $site_settings->site_name . ' Successfully'; 
    }

    public function magic_trick(Request $request){
        if($request->has('reset')){ 
            session(['orders' => null]);
        }
        return view('magic_trick');
    }
    public function magic_trick_store(Request $request){
        
        $order = [
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'model' => $request->model,
            'cost' => $request->cost,
        ];
        
        // Retrieve the existing orders array from the session
        $orders = session('orders', []);

        // Add the new order to the orders array
        $orders[] = $order;

        // Store the updated orders array in the session
        session(['orders' => $orders]);

        return view('magic_trick_table'); 
    }
    
    public function show_qr_code(Request $request){
        $order_num = $request->order_num;
        $bar_code = $request->bar_code;
        return view('partials.qr_code',compact('order_num','bar_code'));
    }
    public function qr_scanner($type){
        $delivery_mans = User::where('user_type','delivery_man')->get();
        return view('admin.playlists.qr_code_scanner',compact('type','delivery_mans'));
    }
    public function barcode_scanner($type){
        $delivery_mans = User::where('user_type','delivery_man')->get();
        return view('admin.playlists.bar_code_scanner',compact('type','delivery_mans'));
    }

    public function bar_code_output(Request $request){
        // s => for receipt social model
        // c => for receipt company model
        // o => for Order model
        $bar_code =  explode('-',$request->code); 

        if($bar_code[0] == 'S'){
            $order = ReceiptSocial::find($bar_code[1]); 
            $model_type = 'social';
        }elseif($bar_code[0] == 'C'){
            $order = ReceiptCompany::find($bar_code[1]); 
            $model_type = 'company';
        }elseif($bar_code[0] == 'O'){
            $order = Order::find($bar_code[1]); 
            $model_type = 'order';
        }else{
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$request->code." Order Not Found</div>"
            ];
        }  
        if(!$order){ 
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$request->code." Order Not Found</div>"
            ];
        }
        if($order->hold){
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$request->code." Order Is Hold - Reason: ".($order->hold_reason ?? "No Reason") ."</div>"
            ];
        }

        if ($request->type == 'design') { 
            $authenticated = $order->designer_id;
        } elseif ($request->type == 'manufacturing') {
            $authenticated = $order->manufacturer_id;
        } elseif ($request->type == 'prepare') {
            $authenticated = $order->preparer_id;
        } elseif ($request->type == 'shipment') {
            $authenticated = $order->shipmenter_id;
        }

        if($order->playlist_status == $request->type){
            if($authenticated == auth()->user()->id || auth()->user()->is_admin  || Gate::allows('transfer_receipts')){
                if($order->playlist_status == 'design'){
                    $next_type = 'manufacturing';
                }elseif($order->playlist_status == 'manufacturing'){
                    $next_type = 'prepare';
                }elseif($order->playlist_status == 'prepare'){
                    $next_type = 'shipment';
                }elseif($order->playlist_status == 'shipment'){
                    $next_type = 'finish';

                    $order->delivery_man_id = $request->delivery_man_id;
                    $order->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
                    $order->delivery_status = 'on_delivery';

                    $order->save();
                }else{
                    return [
                        'status' => 0,
                        'message' => "<div class='alert alert-danger'>".$order->order_num." SomeThing Went Wrong</div>"
                    ];
                }
            }else{
                return [
                    'status' => 0,
                    'message' => "<div class='alert alert-danger'>".$order->order_num." Not Authenticated</div>"
                ];
            }
        }else{ 
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$order->order_num." الطلب في مرحلة مختلفة ".ViewPlaylistData::PLAYLIST_STATUS_SELECT[$order->playlist_status]."</div>"
            ];
        }


        $playlistcontroller = new PlaylistController();
        $array = ['model_type' => $model_type,'id' => $order->id , 'status' => $next_type , 'condition' => 'send'];
        $array0 = new \Illuminate\Http\Request($array);
        return [
            'status' => $playlistcontroller->update_playlist_status($array0),
            'message' => "<div class='alert alert-success'>".$order->order_num." تم الأرسال</div>"
        ];
    }
    public function qr_output(Request $request){

        $order = ReceiptCompany::where('order_num',$request->code)->first(); 
        $model_type = 'company';
        if(!$order){
            $order = Order::where('order_num',$request->code)->first(); 
            $model_type = 'order';
            if(!$order){
                $order = ReceiptSocial::where('order_num',$request->code)->first(); 
                $model_type = 'social';
                if(!$order){
                    return [
                        'status' => 0,
                        'message' => "<div class='alert alert-danger'>".$request->code." Order Not Found</div>"
                    ];
                }
            }
        } 
        
        if($order->hold){
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$request->code." Order Is Hold - Reason: ".($order->hold_reason ?? "No Reason") ."</div>"
            ];
        }

        if ($request->type == 'design') { 
            $authenticated = $order->designer_id;
        } elseif ($request->type == 'manufacturing') {
            $authenticated = $order->manufacturer_id;
        } elseif ($request->type == 'prepare') {
            $authenticated = $order->preparer_id;
        } elseif ($request->type == 'shipment') {
            $authenticated = $order->shipmenter_id;
        }

        if($order->playlist_status == $request->type){
            if($authenticated == auth()->user()->id || auth()->user()->is_admin){
                if($order->playlist_status == 'design'){
                    $next_type = 'manufacturing';
                }elseif($order->playlist_status == 'manufacturing'){
                    $next_type = 'prepare';
                }elseif($order->playlist_status == 'prepare'){
                    $next_type = 'shipment';
                }elseif($order->playlist_status == 'shipment'){
                    $next_type = 'finish';

                    $order->delivery_man_id = $request->delivery_man_id;
                    $order->send_to_delivery_date = date(config('panel.date_format') . ' ' . config('panel.time_format'));
                    $order->delivery_status = 'on_delivery';

                    $order->save();
                }else{
                    return [
                        'status' => 0,
                        'message' => "<div class='alert alert-danger'>".$request->code." SomeThing Went Wrong</div>"
                    ];
                }
            }else{
                return [
                    'status' => 0,
                    'message' => "<div class='alert alert-danger'>".$request->code." Not Authenticated</div>"
                ];
            }
        }else{
            return [
                'status' => 0,
                'message' => "<div class='alert alert-danger'>".$request->code." الطلب في مرحلة مختلفة ".ViewPlaylistData::PLAYLIST_STATUS_SELECT[$order->playlist_status]."</div>"
            ];
        }


        $playlistcontroller = new PlaylistController();
        $array = ['model_type' => $model_type,'id' => $order->id , 'status' => $next_type , 'condition' => 'send'];
        $array0 = new \Illuminate\Http\Request($array);
        return [
            'status' => $playlistcontroller->update_playlist_status($array0),
            'message' => "<div class='alert alert-success'>".$request->code." تم الأرسال</div>"
        ];
    }

    public function receipts_logs(Request $request){
        $crud_name = $request->crud_name;
        $logs = AuditLog::where('subject_type',$request->model)->where('subject_id',$request->subject_id)->orderBy('created_at','asc')->get()->reverse();
        return view('partials.logs',compact('logs','crud_name'));
    }

    public function search_by_phone(Request $request){
        global $phone;
        $phone = $request->phone;
        $are_you_sure = false;
        $receipt_social = ReceiptSocial::where(function ($query) {
                                            $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                    ->orWhere('phone_number_2', 'like', '%'.$GLOBALS['phone'].'%');
                                        })->count();
        $receipt_company = ReceiptCompany::where(function ($query) {
                                            $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                    ->orWhere('phone_number_2', 'like', '%'.$GLOBALS['phone'].'%');
                                        })->count();

        $receipt_client = ReceiptClient::where('phone_number', 'like', '%'.$phone.'%')->count();
        $customers_orders = Order::where('order_type','customer')->where(function ($query) {
                                                                        $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                                                ->orWhere('phone_number_2', 'like', '%'.$GLOBALS['phone'].'%');
                                                                    })->count();
        $sellers_orders = Order::where('order_type','seller')->where(function ($query) {
                                                                        $query->where('phone_number', 'like', '%'.$GLOBALS['phone'].'%')
                                                                                ->orWhere('phone_number_2', 'like', '%'.$GLOBALS['phone'].'%');
                                                                    })->count();

        $banned_phones = BannedPhone::where('phone',$phone)->first();
        if($receipt_social > 0 || $receipt_company > 0 || $receipt_client > 0 || $customers_orders > 0 || $sellers_orders > 0 || $banned_phones > 0 ){
            $are_you_sure = true;
        }
        return view('partials.search_phone',compact('receipt_social','receipt_company','receipt_client','customers_orders','sellers_orders','banned_phones','are_you_sure'));
    }

    public function load_num(Request $request){
        
        $settings1 = [
            'chart_title'           => $request->type,
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\\' . ucfirst($request->type),
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd/m/Y H:i:s',
            'column_class'          => 'col-md-3',
            'entries_number'        => '5',
            'translation_key'       => $request->type,
        ];

        $settings1['total_number'] = 0;
        if (class_exists($settings1['model'])) {
            $settings1['total_number'] = $settings1['model']::when(isset($settings1['filter_field']), function ($query) use ($settings1) {
                if (isset($settings1['filter_days'])) {
                    return $query->where($settings1['filter_field'], '>=',
                        now()->subDays($settings1['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings1['filter_period'])) {
                    switch ($settings1['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings1['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings1['aggregate_function'] ?? 'count'}($settings1['aggregate_field'] ?? '*');
        }

        return response()->json($settings1['total_number'],200);
    }

    public function load_chart(Request $request){
        if($request->type == 'first-chart'){
            $setting = [
                'chart_title'           => __('cruds.order.extra.chart_by_order_type'),
                'chart_type'            => 'doughnut',
                'report_type'           => 'group_by_string',
                'model'                 => 'App\Models\Order',
                'group_by_field'        => 'website_setting_id', 
                'aggregate_function'    => 'count',
                'filter_field'          => 'created_at', 
                'column_class'          => 'col-md-4',
                'entries_number'        => '5',
                'translation_key'       => 'order',
            ];
    
            $chart = new LaravelChart($setting); 
        }elseif($request->type == 'second-chart'){
            $setting = [
                'chart_title'           => __('cruds.receiptSocial.extra.chart_by_month'),
                'chart_type'            => 'radar',
                'report_type'           => 'group_by_date',
                'model'                 => 'App\Models\ReceiptSocial',
                'group_by_field'        => 'created_at',
                'group_by_period'       => 'month',
                'aggregate_function'    => 'sum',
                'aggregate_field'       => 'total_cost',
                'filter_field'          => 'created_at',
                'filter_period'         => 'year',
                'group_by_field_format' => 'd/m/Y  h:i a',
                'column_class'          => 'col-md-4',
                'entries_number'        => '5',
                'translation_key'       => 'receiptSocial',
            ];
    
            $chart = new LaravelChart($setting);
        }elseif($request->type == 'third-chart'){
            $setting = [
                'chart_title'           => __('cruds.receiptSocial.extra.chart_by_website'),
                'chart_type'            => 'doughnut',
                'report_type'           => 'group_by_string',
                'model'                 => 'App\Models\ReceiptSocial',
                'group_by_field'        => 'website_setting_id', 
                'aggregate_function'    => 'count', 
                'filter_field'          => 'created_at', 
                'column_class'          => 'col-md-4',
                'entries_number'        => '5',
                'translation_key'       => 'ReceiptSocial',
            ];
    
            $chart = new LaravelChart($setting);
        }

        return view('partials.chart',compact('chart'));
    }
    public function index()
    {   

        $settings10 = [
            'chart_title'           => __('cruds.order.extra.latest_orders'),
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Order',
            'group_by_field'        => 'done_time',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '30',
            'group_by_field_format' => 'd/m/Y H:i:s',
            'column_class'          => 'col-md-6',
            'entries_number'        => '5',
            'fields'                => [
                'order_num'   => '',
                'client_name' => '',
                'created_at'  => '',
            ],
            'translation_key' => 'order',
        ];

        $settings10['data'] = [];
        if (class_exists($settings10['model'])) {
            $settings10['data'] = $settings10['model']::latest()
                ->take($settings10['entries_number'])
                ->get();
        }

        if (! array_key_exists('fields', $settings10)) {
            $settings10['fields'] = [];
        }

        return view('home', compact( 'settings10'));
    }
}
