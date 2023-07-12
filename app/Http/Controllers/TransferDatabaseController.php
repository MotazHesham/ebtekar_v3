<?php

namespace App\Http\Controllers;

use App\Models\BannedPhone;
use App\Models\Category;
use App\Models\Color;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestOrders;
use App\Models\Country;
use App\Models\Customer;
use App\Models\DeliverMan;
use App\Models\ExcelFile;
use App\Models\FaqCategory;
use App\Models\FaqQuestion;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Police;
use App\Models\Printable;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ReceiptClient;
use App\Models\ReceiptClientProduct;
use App\Models\ReceiptClientProductPivot;
use App\Models\ReceiptCompany;
use App\Models\ReceiptOutgoing;
use App\Models\ReceiptOutgoingProduct;
use App\Models\ReceiptPriceView;
use App\Models\ReceiptPriceViewProduct;
use App\Models\ReceiptSocial;
use App\Models\ReceiptSocialProduct;
use App\Models\ReceiptSocialProductPivot;
use App\Models\Search;
use App\Models\Seller;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransferDatabaseController extends Controller
{
    public function mixed(){   

        
        $excels = DB::connection('mysql_second')->table('excel_files')->get();  
        foreach($excels as $raw){ 
            $excel = new ExcelFile();
            $excel->id = $raw->id;     
            $excel->type = $raw->type;
            $excel->results = $raw->results;   
            $excel->created_at = $raw->created_at;
            $excel->updated_at = $raw->updated_at;
            $excel->save();
            if($raw->uploaded_file){ 
                if(file_exists('public/'.$raw->uploaded_file)){
                    $excel->addMedia('public/'.$raw->uploaded_file)->toMediaCollection('uploaded_file');
                } 
            }
            if($raw->result_file){ 
                if(file_exists('public/'.$raw->result_file)){
                    $excel->addMedia('public/'.$raw->result_file)->toMediaCollection('result_file');
                } 
            } 
        }
        
        $searches = DB::connection('mysql_second')->table('searches')->get(); 
        foreach($searches as $raw){ 
            $search = new Search();
            $search->id = $raw->id;     
            $search->search = $raw->query;
            $search->count = $raw->count;  
            $search->created_at = $raw->created_at;
            $search->updated_at = $raw->updated_at;
            $search->save();
        }

        $printables = DB::connection('mysql_second')->table('printable')->get(); 
        foreach($printables as $raw){ 
            $printable = new Printable();
            $printable->id = $raw->id;     
            $printable->printable_model = $raw->printable_model;
            $printable->user_id = $raw->user_id; 
            $printable->printable_id = $raw->printable_id; 
            $printable->created_at = $raw->created_at;
            $printable->updated_at = $raw->updated_at;
            $printable->save();
        }

        $policies = DB::connection('mysql_second')->table('policies')->get(); 
        foreach($policies as $raw){ 
            $police = new Police();
            $police->id = $raw->id;    
            $police->name = $raw->name;
            $police->content = $raw->content; 
            $police->created_at = $raw->created_at;
            $police->updated_at = $raw->updated_at;
            $police->save();
        }

        $questions = DB::connection('mysql_second')->table('common_questions')->get();
        $faq_category = FaqCategory::create([
            'category' => 'Seller Questions',
        ]);
        foreach($questions as $raw){ 
            $faq_question = new FaqQuestion();
            $faq_question->id = $raw->id; 
            $faq_question->category_id = $faq_category->id; 
            $faq_question->question = $raw->question;
            $faq_question->answer = $raw->answer; 
            $faq_question->created_at = $raw->created_at;
            $faq_question->updated_at = $raw->updated_at;
            $faq_question->save();
        }


        $countries = DB::connection('mysql_second')->table('countries')->get(); 
        foreach($countries as $raw){ 
            $country = new Country();
            $country->id = $raw->id;
            $country->name = $raw->name;
            $country->cost = $raw->cost;
            $country->code = $raw->code;
            $country->code_cost = $raw->code_cost;
            $country->type = $raw->type;
            $country->status = $raw->status;
            $country->website = $raw->website; 
            $country->created_at = $raw->created_at;
            $country->updated_at = $raw->updated_at;
            $country->save();
        }

        $colors = DB::connection('mysql_second')->table('colors')->get();
        foreach($colors as $raw){ 
            $color = new Color();
            $color->id = $raw->id;
            $color->name = $raw->name;
            $color->code = $raw->code;
            $color->created_at = $raw->created_at;
            $color->updated_at = $raw->updated_at;
            $color->save();
        }
        $banned_phones = DB::connection('mysql_second')->table('banned_phones')->get();
        foreach($banned_phones as $raw){ 
            $banned = new BannedPhone();
            $banned->id = $raw->id;
            $banned->phone = $raw->phone;
            $banned->reason = $raw->reason;
            $banned->created_at = $raw->created_at;
            $banned->updated_at = $raw->updated_at;
            $banned->save();
        }

        return 'success';
    }

    public function transfer_users(){   
        $users = DB::connection('mysql_second')->table('users')->get();
        foreach($users as $raw){ 
            $user = new User(); 
            $user->id = $raw->id;
            $user->providerid = $raw->provider_id;
            $user->user_type = $raw->user_type == 'admin' ? 'staff' : $raw->user_type;
            $user->name = $raw->name;
            $user->email = $raw->email;
            $user->phone_number = $raw->phone;
            $user->address = $raw->address; 
            $user->verified = 1;
            $user->verified_at = $raw->email_verified_at ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->email_verified_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null; 
            $user->password = $raw->password;
            $user->wasla_company_id = $raw->wasla_company_id;
            $user->wasla_token = $raw->wasla_token;
            $user->device_token = $raw->device_token;
            $user->email_verified_at = $raw->email_verified_at ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->email_verified_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
            $user->remember_token = $raw->remember_token;
            $user->website_setting_id = 1;
            $user->created_at = $raw->created_at;
            $user->updated_at = $raw->updated_at;
            $user->save();

            if($user->user_type == 'customer'){
                Customer::create([
                    'user_id' => $user->id,
                ]);
            }elseif($user->user_type == 'seller'){
                $old_seller = DB::connection('mysql_second')->table('sellers')->where('user_id',$user->id)->first();
                if($old_seller){ 
                    $seller = new Seller(); 
                    $seller->id = $old_seller->id;
                    $seller->user_id = $old_seller->user_id;
                    $seller->seller_type = $old_seller->seller_type;
                    $seller->discount = $old_seller->discount;
                    $seller->discount_code = $old_seller->discount_code;
                    $seller->order_out_website = $old_seller->order_out_website;
                    $seller->order_in_website = $old_seller->order_in_website;
                    $seller->qualification = $old_seller->qualification;
                    $seller->social_name = $old_seller->social_name;
                    $seller->social_link = $old_seller->social_link;
                    $seller->seller_code = $old_seller->seller_code;
                    $seller->save();
                }
            }elseif($user->user_type == 'delivery_man'){
                DeliverMan::create([
                    'user_id' => $user->id,
                ]);
            }
        }
        foreach(User::where('user_type','seller')->get() as $user){
            if(!Seller::where('user_id',$user->id)->first()){
                $seller = new Seller(); 
                $seller->user_id = $user->id;
                $seller->seller_type = 'seller';
                $seller->save();
            }
        }
        return User::all();

    }
    public function transfer_categories(){  
        $categories = DB::connection('mysql_second')->table('categories')->get();
        foreach($categories as $raw){ 
            $category = new Category();
            
            $category->id = $raw->id;
            $category->name = $raw->name;
            $category->slug = $raw->slug;
            $category->design = $raw->design;
            $category->featured = $raw->featured;
            $category->meta_title = $raw->meta_title;
            $category->meta_description = $raw->meta_description;
            $category->website_setting_id = 1;
            $category->created_at = $raw->created_at;
            $category->updated_at = $raw->updated_at;
            $category->save();

            if($raw->banner &&  file_exists('public/'.$raw->banner)){
                $category->addMedia('public/'.$raw->banner)->toMediaCollection('banner');
            }
            if($raw->icon &&  file_exists('public/'.$raw->icon)){
                $category->addMedia('public/'.$raw->icon)->toMediaCollection('icon'); 
            }
        }
        return Category::all();
    }
    
    public function transfer_sub_categories(){  
        $sub_categories = DB::connection('mysql_second')->table('sub_categories')->get();
        foreach($sub_categories as $raw){ 
            $sub_category = new SubCategory();
            
            $sub_category->id = $raw->id;
            $sub_category->name = $raw->name;
            $sub_category->slug = $raw->slug;
            $sub_category->design = $raw->design; 
            $sub_category->meta_title = $raw->meta_title;
            $sub_category->meta_description = $raw->meta_description;
            $sub_category->website_setting_id = 1;
            $sub_category->category_id = $raw->category_id;
            $sub_category->created_at = $raw->created_at;
            $sub_category->updated_at = $raw->updated_at;
            $sub_category->save(); 
        }
        return SubCategory::all();
    }
    public function transfer_sub_sub_categories(){  
        $sub_sub_categories = DB::connection('mysql_second')->table('sub_sub_categories')->get();
        foreach($sub_sub_categories as $raw){ 
            $sub_sub_category = new SubSubCategory();
            
            $sub_sub_category->id = $raw->id;
            $sub_sub_category->name = $raw->name;
            $sub_sub_category->slug = $raw->slug;
            $sub_sub_category->design = $raw->design; 
            $sub_sub_category->meta_title = $raw->meta_title;
            $sub_sub_category->meta_description = $raw->meta_description;
            $sub_sub_category->website_setting_id = 1;
            $sub_sub_category->sub_category_id = $raw->sub_category_id;
            $sub_sub_category->created_at = $raw->created_at;
            $sub_sub_category->updated_at = $raw->updated_at;
            $sub_sub_category->save(); 
        }
        return SubSubCategory::all();
    }
    
    public function transfer_products(){ 
        $products = DB::connection('mysql_second')->table('products')->get();
        foreach($products as $raw){ 
            $product = new Product(); 
            if(!Product::find($raw->id)){
                $product->id = $raw->id; 
                $product->name = $raw->name;
                $product->weight = 'half_kg';
                $product->added_by = $raw->added_by;
                $product->unit_price = $raw->unit_price;
                $product->purchase_price = $raw->purchase_price;
                $product->slug = $raw->slug;
                $product->attributes = $raw->attributes;
                $product->attribute_options = $raw->choice_options;
                $product->colors = $raw->colors;
                $product->tags = $raw->tags;
                $product->video_provider = $raw->video_provider;
                $product->video_link = $raw->video_link;
                $product->description = $raw->description;
                $product->discount_type = $raw->discount_type == 'amount' ? 'flat' : $raw->discount_type ;
                $product->discount = $raw->discount;
                $product->meta_title = $raw->meta_title;
                $product->meta_description = $raw->meta_description;
                $product->flash_deal = 0;
                $product->published = $raw->published;
                $product->featured = $raw->featured;
                $product->todays_deal = $raw->todays_deal;
                $product->special = 1;
                $product->variant_product = $raw->variant_product;
                $product->rating = $raw->rating;
                $product->current_stock = $raw->current_stock;
                if($raw->user_id && User::find($raw->user_id)){
                    $product->user_id = $raw->user_id;
                }else{
                    $product->user_id = 1;
                }
                if($raw->category_id && Category::find($raw->category_id)){
                    $product->category_id = $raw->category_id;
                }
                if($raw->subcategory_id && SubCategory::find($raw->subcategory_id)){
                    $product->sub_category_id = $raw->subcategory_id;
                }
                if($raw->subsubcategory_id && SubSubCategory::find($raw->subsubcategory_id)){
                    $product->sub_sub_category_id = $raw->subsubcategory_id; 
                }
                $product->website_setting_id = 1;
                $product->created_at = $raw->created_at;
                $product->updated_at = $raw->updated_at;
                $product->save();
                if($raw->photos){
                    foreach(json_decode($raw->photos) as $photo){
                        if(file_exists('public/'.$photo)){
                            $product->addMedia('public/'.$photo)->toMediaCollection('photos');
                        } 
                    }
                } 
            }
        }
        return Product::all();
    }

    public function transfer_products_stock(){ 
        $product_stocks = DB::connection('mysql_second')->table('product_stocks')->get();
        foreach($product_stocks as $raw){ 
            $product_stock = new ProductStock();
            if(Product::find($raw->product_id)){
                $product_stock->id = $raw->id;
                $product_stock->product_id = $raw->product_id;
                $product_stock->variant = $raw->variant; 
                $product_stock->stock = $raw->qty;
                $product_stock->unit_price = $raw->price;
                $product_stock->purchase_price = $raw->purchase_price;
                $product_stock->created_at = $raw->created_at;
                $product_stock->updated_at = $raw->updated_at; 
                $product_stock->save(); 
            }
        }
        return ProductStock::all();
    }

    public function transfer_orders(){ 
        $orders = DB::connection('mysql_second')->table('orders')->get();
        foreach($orders as $raw){ 
            $order = new Order(); 
            $order->id = $raw->id;
            $order->paymob_orderid = $raw->paymob_order_id;
            $order->order_type = $raw->order_type;
            $order->exchange_rate = 1.00;
            $order->symbol = 'EGP';
            $order->order_num = $raw->code;
            $order->client_name = $raw->client_name;
            $order->phone_number = $raw->phone_number;
            $order->phone_number_2 = $raw->phone_number2;
            $order->shipping_address = $raw->shipping_address ?? 'address'; 
            $order->shipping_country_cost = $raw->shipping_country_cost;
            $order->shipping_cost_by_seller = $raw->shipping_cost_by_seller;
            $order->free_shipping = $raw->free_shipping ?? 0;
            $order->free_shipping_reason = $raw->free_shipping_reason;
            $order->printing_times = 1;
            $order->completed = $raw->completed;
            $order->calling = $raw->calling;
            $order->quickly = 0;
            $order->supplied = $raw->supplied;
            $order->done_time = $raw->done_time ? date(config('panel.date_format') . ' ' . config('panel.time_format'),$raw->done_time) : null;
            if($raw->send_to_deliveryman_date != '0000-00-00 00:00:00'){
                $order->send_to_delivery_date = $raw->send_to_deliveryman_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_deliveryman_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
            }
            if($raw->send_to_playlist_date != '0000-00-00 00:00:00'){ 
                $order->send_to_playlist_date = $raw->send_to_playlist_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_playlist_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
            } 
            $order->date_of_receiving_order = $raw->date_of_receiving_order ? date(config('panel.date_format'),$raw->date_of_receiving_order) : null;
            $order->excepected_deliverd_date = $raw->excepected_deliverd_date ? date(config('panel.date_format'),$raw->excepected_deliverd_date) : null;
            $order->playlist_status = $this->change_playlist($raw->playlist_status);
            $order->payment_status = $raw->payment_status;
            $order->delivery_status = $raw->delivery_status;
            $order->payment_type = $raw->payment_type;
            $order->commission_status = $raw->commission_status;
            $order->deposit_type = $raw->deposit;
            $order->deposit_amount = $raw->deposit_amount;
            $order->total_cost_by_seller = $raw->total_cost_by_seller;
            $order->total_cost = $raw->required_to_pay;
            $order->commission = $raw->commission;
            $order->extra_commission = $raw->extra_commission;
            $order->discount = $raw->discount ?? 0;
            $order->discount_code = $raw->discount_code;
            $order->note = $raw->note;
            $order->cancel_reason = $raw->cancel_reason;
            $order->delay_reason = $raw->delay_reason;
            $order->user_id = $raw->user_id ? User::find($raw->user_id)->id ?? null : null;
            $order->shipping_country_id = $raw->shipping_country_id ? Country::find($raw->shipping_country_id)->id ?? null : null;
            $order->designer_id = $raw->designer_id ? User::find($raw->designer_id)->id ?? null : null;
            $order->preparer_id = $raw->preparer_id ? User::find($raw->preparer_id)->id ?? null : null;
            $order->manufacturer_id = $raw->manifacturer_id ? User::find($raw->manifacturer_id)->id ?? null : null;
            $order->shipmenter_id = $raw->send_to_delivery_id ? User::find($raw->send_to_delivery_id)->id ?? null : null;
            $order->delivery_man_id = $raw->delivery_man ? User::find($raw->delivery_man)->id ?? null : null;
            $order->website_setting_id = 1;
            $order->created_at = $raw->created_at;
            $order->updated_at = $raw->updated_at; 
            $order->save();  
        }
        return 'success';
    }  
    
    public function transfer_orders_details(){ 
        $order_details = DB::connection('mysql_second')->table('order_details')->get(); 
        foreach($order_details as $raw){ 
            $orderDetail = new OrderDetail(); 
            if(Order::find($raw->order_id)){
                $photos = array();
                if($raw->photos){
                    foreach(json_decode($raw->photos) as $key => $photo){ 
                        $photos[$key]['photo'] = $photo;
                        $photos[$key]['note'] = json_decode($raw->photos_note)[$key];  
                    }
                }
                $orderDetail->id = $raw->id;
                $orderDetail->order_id = $raw->order_id;
                $orderDetail->product_id = $raw->product_id ? Product::find($raw->product_id)->id ?? null : null;
                $orderDetail->variation = $raw->variation;
                $orderDetail->commission = $raw->commission; 
                $orderDetail->extra_commission = $raw->extra_commission; 
                $orderDetail->total_cost = $raw->total_cost; 
                $orderDetail->quantity = $raw->quantity; 
                $orderDetail->price = $raw->price; 
                $orderDetail->weight_price = 0; 
                $orderDetail->photos = json_encode($photos);  
                $orderDetail->link = $raw->link; 
                $orderDetail->pdf = $raw->pdf; 
                $orderDetail->email_sent = $raw->email_sent; 
                $orderDetail->pdf = $raw->pdf; 
                $orderDetail->description = $raw->description; 
                $orderDetail->created_at = $raw->created_at;
                $orderDetail->updated_at = $raw->updated_at; 
                $orderDetail->save();  
            }
        }
        return 'success';
    }
    
    public function transfer_commission_request(){ 
        $commission_requests = DB::connection('mysql_second')->table('commission_request')->get(); 
        foreach($commission_requests as $raw){ 
            $commission_request = new CommissionRequest(); 
            if(User::find($raw->user_id)){
                $commission_request->id = $raw->id;
                $commission_request->user_id = $raw->user_id;
                $commission_request->created_by_id = $raw->by_user_id ? User::find($raw->by_user_id)->id ?? null : null;
                $commission_request->status = $raw->status;
                $commission_request->total_commission = $raw->total_commission;
                $commission_request->payment_method = $raw->payment_method;
                $commission_request->transfer_number = $raw->transfer_number;
                $commission_request->done_time = $raw->done_time ? date(config('panel.date_format') . ' ' . config('panel.time_format'),$raw->done_time) : null;
                $commission_request->done_by_user_id = $raw->done_by_user_id ? User::find($raw->done_by_user_id)->id ?? null : null;
                $commission_request->created_at = $raw->created_at;
                $commission_request->updated_at = $raw->updated_at; 
                $commission_request->save();
            }
        }

        $commission_request_orders = DB::connection('mysql_second')->table('commission_request_orders')->get(); 
        foreach($commission_request_orders as $raw){
            $commission_request_order = new CommissionRequestOrders();  
            if(CommissionRequest::find($raw->commission_request_id)){
                $commission_request_order->commission_request_id = $raw->commission_request_id; 
                $commission_request_order->order_id = $raw->order_id ? Order::find($raw->order_id)->id ?? null : null; 
                $commission_request_order->commission = $raw->commission;  
                $commission_request_order->created_at = $raw->created_at;
                $commission_request_order->updated_at = $raw->updated_at; 
                $commission_request_order->save();
            }
        }

        return 'success';
    }

    public function receipt_products(){
        $receipt_products = DB::connection('mysql_second')->table('receipt_products')->get(); 
        foreach($receipt_products as $raw){
            if($raw->type == 'figures'){
                $receipt_social_product = new ReceiptSocialProduct();   
                $receipt_social_product->id = $raw->id;
                $receipt_social_product->name = $raw->name;  
                $receipt_social_product->price = $raw->price;   
                $receipt_social_product->commission = $raw->commission ?? 0;   
                $receipt_social_product->created_at = $raw->created_at;
                $receipt_social_product->updated_at = $raw->updated_at; 
                $receipt_social_product->save();  
                if($raw->photos){
                    foreach(json_decode($raw->photos) as $photo){
                        if(file_exists('public/'.$photo)){
                            $receipt_social_product->addMedia('public/'.$photo)->toMediaCollection('photos');
                        } 
                    }
                } 
            } 
        }
        return 'success';
    }

    public function receipt_clients(){
        $receipt_clients = DB::connection('mysql_second')->table('receipt_clients')->get(); 
        foreach($receipt_clients as $raw){ 
            $receipt_client = new ReceiptClient();   
            $receipt_client->id = $raw->id;
            $receipt_client->date_of_receiving_order = $raw->date_of_receiving_order ? date(config('panel.date_format'),$raw->date_of_receiving_order) : null;
            $receipt_client->order_num = $raw->order_num;
            $receipt_client->client_name = $raw->client_name ?? 'None';
            $receipt_client->phone_number = $raw->phone ?? 0;
            $receipt_client->deposit = $raw->deposit;
            $receipt_client->discount = $raw->discount;
            $receipt_client->note = $raw->note;
            $receipt_client->total_cost = $raw->total;
            $receipt_client->done = $raw->done;
            $receipt_client->quickly = $raw->quickly;
            $receipt_client->printing_times = $raw->viewed;
            $receipt_client->staff_id = $raw->staff_id ? User::find($raw->staff_id)->id ?? null : null;
            $receipt_client->website_setting_id = 1; 
            $receipt_client->created_at = $raw->created_at;
            $receipt_client->updated_at = $raw->updated_at; 
            $receipt_client->save();
        } 
        return 'success';
    }

    
    public function receipt_client_products(){
        $receipt_client_products = DB::connection('mysql_second')->table('receipt_client_products')->get(); 
        foreach($receipt_client_products as $raw){  
            $receipt_client_product = new ReceiptClientProductPivot();    
            if(ReceiptClient::find($raw->receipt_client_id)){ 
                $receipt_client_product->id = $raw->id; 
                $receipt_client_product->description = $raw->description;
                $receipt_client_product->quantity = $raw->quantity ?? 1;
                $receipt_client_product->price = $raw->cost ?? 0;
                $receipt_client_product->total_cost = $raw->total ?? 0;
                $receipt_client_product->receipt_client_id = $raw->receipt_client_id;
                $tmp = ReceiptClientProduct::where('name',$raw->description)->first();
                if($tmp){
                    $receipt_client_product->receipt_client_product_id = $tmp->id; 
                }
                $receipt_client_product->created_at = $raw->created_at;
                $receipt_client_product->updated_at = $raw->updated_at; 
                $receipt_client_product->save(); 
            }
        }
        return 'success';
    }

    public function receipt_socials(){
        // ini_set('max_execution_time', 600);
        $receipt_socials = DB::connection('mysql_second')->table('receipt_social')->get(); 
        foreach($receipt_socials as $raw){ 
            if(!ReceiptSocial::find($raw->id)){ 
                $receipt_social = new ReceiptSocial();   
                $receipt_social->id = $raw->id;
                $receipt_social->order_num = $raw->order_num;
                $receipt_social->client_name = $raw->client_name ?? 'None';
                $receipt_social->client_type = $raw->type ?? 'individual';
                $receipt_social->phone_number = $raw->phone  ?? 0;
                $receipt_social->phone_number_2 = $raw->phone2;
                $receipt_social->deposit = $raw->deposit;
                $receipt_social->discount = $raw->discount;
                $receipt_social->commission = $raw->commission;
                $receipt_social->extra_commission = $raw->extra_commission;
                $receipt_social->total_cost = $raw->total;
                $receipt_social->done = $raw->done;
                $receipt_social->quickly = $raw->quickly;
                $receipt_social->confirm = $raw->confirm;
                $receipt_social->returned = $raw->returned;
                $receipt_social->supplied = $raw->supplied;
                $receipt_social->printing_times = $raw->viewed; 
                $receipt_social->shipping_country_cost = $raw->shipping_country_cost; 
                $receipt_social->shipping_address = $raw->address ?? 'none';
                $receipt_social->date_of_receiving_order = $raw->date_of_receiving_order ? date(config('panel.date_format'),$raw->date_of_receiving_order) : null;
                $receipt_social->deliver_date = $raw->deliver_date ? date(config('panel.date_format'),$raw->deliver_date) : null;
                if($raw->send_to_deliveryman_date != '0000-00-00 00:00:00'){
                    $receipt_social->send_to_delivery_date = $raw->send_to_deliveryman_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_deliveryman_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
                }
                if($raw->send_to_playlist_date != '0000-00-00 00:00:00'){
                    $receipt_social->send_to_playlist_date = $raw->send_to_playlist_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_playlist_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
                }
                $receipt_social->done_time = $raw->done_time ? date(config('panel.date_format') . ' ' . config('panel.time_format'),$raw->done_time) : null;
                $receipt_social->cancel_reason = $raw->cancel_reason;
                $receipt_social->delay_reason = $raw->delay_reason;
                $receipt_social->delivery_status = $raw->delivery_status;
                $receipt_social->note = $raw->note;
                $receipt_social->payment_status = $raw->payment_status;
                $receipt_social->playlist_status = $this->change_playlist($raw->playlist_status);
                $receipt_social->staff_id = $raw->staff_id ? User::find($raw->staff_id)->id ?? null : null;
                $receipt_social->designer_id = $raw->designer_id ? User::find($raw->designer_id)->id ?? null : null;
                $receipt_social->preparer_id = $raw->preparer_id ? User::find($raw->preparer_id)->id ?? null : null;
                $receipt_social->manufacturer_id = $raw->manifacturer_id ? User::find($raw->manifacturer_id)->id ?? null : null;
                $receipt_social->shipmenter_id = $raw->send_to_delivery_id ? User::find($raw->send_to_delivery_id)->id ?? null : null;
                $receipt_social->delivery_man_id = $raw->delivery_man ? User::find($raw->delivery_man)->id ?? null : null;
                $receipt_social->shipping_country_id = $raw->shipping_country_id ? Country::find($raw->shipping_country_id)->id ?? null : null;
                $receipt_social->website_setting_id = $raw->receipt_type == 'social' ? 1 : 3;
                $receipt_social->created_at = $raw->created_at;
                $receipt_social->updated_at = $raw->updated_at;
                $receipt_social->deleted_at = $raw->trash ? $raw->updated_at : null;
                $receipt_social->save();        
            } 
        }
        return 'success';
    }
    
    
    public function receipt_social_products(){ 
        $receipt_social_products = DB::connection('mysql_second')->table('receipt_social_products')->get(); 
        foreach($receipt_social_products as $raw){ 
            $receipt_social_product = new ReceiptSocialProductPivot();    
            if(ReceiptSocial::find($raw->receipt_social_id) && !ReceiptSocialProductPivot::find($raw->id)){ 
                $receipt_social_product->id = $raw->id; 
                $receipt_social_product->title = $raw->title;
                $receipt_social_product->description = $raw->description;
                $receipt_social_product->quantity = $raw->quantity ?? 1;
                $receipt_social_product->price = $raw->cost ?? 0;
                $receipt_social_product->commission = $raw->commission ?? 0;
                $receipt_social_product->extra_commission = $raw->extra_commission;
                $receipt_social_product->total_cost = $raw->total ?? 0;
                $receipt_social_product->pdf = $raw->pdf; 
                $receipt_social_product->receipt_social_id = $raw->receipt_social_id; 
                $receipt_social_product->receipt_social_product_id = $raw->receipt_product_id ? ReceiptSocialProduct::find($raw->receipt_product_id)->id ?? null : null; 
                $receipt_social_product->created_at = $raw->created_at;
                $receipt_social_product->updated_at = $raw->updated_at;  
                $photos = array();
                if($raw->photos){
                    foreach(json_decode($raw->photos) as $key => $photo){ 
                        $photos[$key]['photo'] = $photo;
                        $photos[$key]['note'] = json_decode($raw->photos_note)[$key] ?? null;  
                    }
                }
                $receipt_social_product->photos = json_encode($photos);
                $receipt_social_product->save(); 
            } 
        }
        return 'success';
    }

    public function receipt_outgoings(){
        $receipt_outgoings = DB::connection('mysql_second')->table('receipt_outgoings')->get(); 
        foreach($receipt_outgoings as $raw){  
            $receipt_outgoing = new ReceiptOutgoing();   
            $receipt_outgoing->id = $raw->id;
            $receipt_outgoing->date_of_receiving_order = $raw->date_of_receiving_order ? date(config('panel.date_format'),$raw->date_of_receiving_order) : null;
            $receipt_outgoing->order_num = $raw->order_num;
            $receipt_outgoing->client_name = $raw->client_name ?? 'None';
            $receipt_outgoing->phone_number = $raw->phone ?? 0; 
            $receipt_outgoing->note = $raw->note;
            $receipt_outgoing->total_cost = $raw->total;
            $receipt_outgoing->done = $raw->done; 
            $receipt_outgoing->printing_times = $raw->viewed;
            $receipt_outgoing->staff_id = $raw->staff_id ? User::find($raw->staff_id)->id ?? null : null; 
            $receipt_outgoing->created_at = $raw->created_at;
            $receipt_outgoing->updated_at = $raw->updated_at; 
            $receipt_outgoing->save(); 
        }  

        $receipt_outgoings_products = DB::connection('mysql_second')->table('receipt_outgoings_products')->get(); 
        foreach($receipt_outgoings_products as $raw){  
            $receipt_outgoings_product = new ReceiptOutgoingProduct();   
            if(!ReceiptOutgoingProduct::find($raw->id) && ReceiptOutgoing::find($raw->receipt_outgoings_id)){
                $receipt_outgoings_product->id = $raw->id;
                $receipt_outgoings_product->receipt_outgoing_id = $raw->receipt_outgoings_id; 
                $receipt_outgoings_product->quantity = $raw->quantity;
                $receipt_outgoings_product->price = $raw->cost;
                $receipt_outgoings_product->total_cost = $raw->total; 
                $receipt_outgoings_product->description = $raw->description; 
                $receipt_outgoings_product->created_at = $raw->created_at;
                $receipt_outgoings_product->updated_at = $raw->updated_at; 
                $receipt_outgoings_product->save(); 
            } 
        }   
        return 'success';
    }

    public function receipt_price_view(){
        $receipt_price_views = DB::connection('mysql_second')->table('receipt_price_view')->get(); 
        foreach($receipt_price_views as $raw){  
            $receipt_price_view = new ReceiptPriceView();   
            $receipt_price_view->id = $raw->id; 
            $receipt_price_view->order_num = $raw->order_num;
            $receipt_price_view->client_name = $raw->client_name ?? 'None'; 
            $receipt_price_view->relate_duration = $raw->relate_duration;
            $receipt_price_view->supply_duration = $raw->supply_duration;
            $receipt_price_view->payment = $raw->payment; 
            $receipt_price_view->place = $raw->place; 
            $receipt_price_view->added_value = $raw->added_value; 
            $receipt_price_view->phone_number = $raw->phone; 
            $receipt_price_view->total_cost = $raw->total;
            $receipt_price_view->printing_times = 1;
            $receipt_price_view->staff_id = $raw->staff_id ? User::find($raw->staff_id)->id ?? null : null; 
            $receipt_price_view->website_setting_id = 1;
            $receipt_price_view->created_at = $raw->created_at;
            $receipt_price_view->updated_at = $raw->updated_at; 
            $receipt_price_view->save(); 
        }   

        $receipt_price_view_products = DB::connection('mysql_second')->table('receipt_price_view_products')->get(); 
        foreach($receipt_price_view_products as $raw){  
            $receipt_price_view_product = new ReceiptPriceViewProduct();   
            if(!ReceiptPriceViewProduct::find($raw->id) && ReceiptPriceView::find($raw->receipt_price_view_id)){
                $receipt_price_view_product->id = $raw->id;
                $receipt_price_view_product->receipt_price_view_id = $raw->receipt_price_view_id; 
                $receipt_price_view_product->quantity = $raw->quantity;
                $receipt_price_view_product->price = $raw->cost;
                $receipt_price_view_product->total_cost = $raw->total; 
                $receipt_price_view_product->description = $raw->description; 
                $receipt_price_view_product->created_at = $raw->created_at;
                $receipt_price_view_product->updated_at = $raw->updated_at; 
                $receipt_price_view_product->save(); 
            } 
        }
        return 'success';
    }

    
    public function receipt_companies(){
        $receipt_companies = DB::connection('mysql_second')->table('receipt_comapny')->get(); 
        foreach($receipt_companies as $raw){  
            $receipt_company = new ReceiptCompany();   
            $receipt_company->id = $raw->id;
            $receipt_company->order_num = $raw->order_num;
            $receipt_company->client_name = $raw->client_name ?? 'none';
            $receipt_company->client_type = $raw->type ?? 'individual';
            $receipt_company->phone_number = $raw->phone ?? 0;
            $receipt_company->phone_number_2 = $raw->phone2;
            $receipt_company->deposit = $raw->deposit;
            $receipt_company->total_cost = $raw->order_cost;
            $receipt_company->calling = $raw->calling;
            $receipt_company->quickly = $raw->quickly;
            $receipt_company->done = $raw->done;
            $receipt_company->no_answer = $raw->no_answer;
            $receipt_company->supplied = $raw->supplied;
            $receipt_company->printing_times = $raw->viewed;  
            $receipt_company->date_of_receiving_order = $raw->date_of_receiving_order ? date(config('panel.date_format'),$raw->date_of_receiving_order) : null;
            $receipt_company->deliver_date = $raw->deliver_date ? date(config('panel.date_format'),$raw->deliver_date) : null;
            if($raw->send_to_deliveryman_date != '0000-00-00 00:00:00'){
                $receipt_company->send_to_delivery_date = $raw->send_to_deliveryman_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_deliveryman_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
            }
            if($raw->send_to_playlist_date != '0000-00-00 00:00:00'){
                $receipt_company->send_to_playlist_date = $raw->send_to_playlist_date ? Carbon::createFromFormat('Y-m-d H:i:s', $raw->send_to_playlist_date)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
            }
            $receipt_company->done_time = $raw->done_time ? date(config('panel.date_format') . ' ' . config('panel.time_format'),$raw->done_time) : null; 
            $receipt_company->shipping_country_cost = $raw->shipping_country_cost;
            $receipt_company->shipping_address = $raw->address ?? 'address';
            $receipt_company->description = $raw->description ?? 'none';
            $receipt_company->note = $raw->note;
            $receipt_company->cancel_reason = $raw->cancel_reason;
            $receipt_company->delay_reason = $raw->delay_reason;
            $receipt_company->delivery_status = $raw->delivery_status;
            $receipt_company->payment_status = $raw->payment_status;
            $receipt_company->playlist_status = $this->change_playlist($raw->playlist_status); 
            $receipt_company->staff_id = $raw->staff_id ? User::find($raw->staff_id)->id ?? null : null;
            $receipt_company->designer_id = $raw->designer_id ? User::find($raw->designer_id)->id ?? null : null;
            $receipt_company->preparer_id = $raw->preparer_id ? User::find($raw->preparer_id)->id ?? null : null;
            $receipt_company->manufacturer_id = $raw->manifacturer_id ? User::find($raw->manifacturer_id)->id ?? null : null;
            $receipt_company->shipmenter_id = $raw->send_to_delivery_id ? User::find($raw->send_to_delivery_id)->id ?? null : null;
            $receipt_company->delivery_man_id = $raw->delivery_man ? User::find($raw->delivery_man)->id ?? null : null;
            $receipt_company->shipping_country_id = $raw->shipping_country_id ? Country::find($raw->shipping_country_id)->id ?? null : null; 
            $receipt_company->created_at = $raw->created_at;
            $receipt_company->updated_at = $raw->updated_at; 
            $receipt_company->deleted_at = $raw->trash ? $raw->updated_at : null; 
            $receipt_company->save(); 
            if($raw->photos){
                foreach(json_decode($raw->photos) as $photo){
                    if(file_exists('public/'.$photo)){
                        $receipt_company->addMedia('public/'.$photo)->toMediaCollection('photos');
                    } 
                }
            } 
        } 
    }

    public function change_playlist($status){
        switch ($status) {
            case 'pending':  
                return 'pending'; 
            case 'finish':  
                return 'finish'; 
            case 'design':  
                return 'design'; 
            case 'manufacturing': 
                return 'manufacturing'; 
            case 'prepare': 
                return 'prepare'; 
            case 'send_to_delivery': 
                return 'shipment'; 
        }
    }
}
