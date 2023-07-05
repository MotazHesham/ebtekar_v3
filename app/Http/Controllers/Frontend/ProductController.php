<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product($slug){ 
        $site_settings = get_site_setting();
        $product  = Product::where('website_setting_id',$site_settings->id)->where('slug', $slug)->first();
        if(!$product){
            abort(404);
        }
        return view('frontend.product',compact('product'));
    }

    public function quick_view(Request $request){
        $product  = Product::findOrFail($request->id);
        return view('frontend.partials.quick_view',compact('product'));
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $available_quantity = 0;

        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if(json_decode(Product::find($request->id)->attribute_options) != null){
            foreach (json_decode(Product::find($request->id)->attribute_options) as $key => $attr_optn) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_'.$attr_optn->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_'.$attr_optn->attribute_id]);
                }
            }
        }

        if($str != null){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->unit_price;
            $comission = ( $product_stock->unit_price - $product_stock->purchase_price);
            $available_quantity = $product_stock->quantity;
        }else{
            $price = $product->unit_price;
            $comission = ( $product->unit_price -$product->purchase_price );
            $available_quantity = $product->current_stock;
        }

        //discount calculation
        $before_discount = 0;
        if($product->discount_type == 'percent'){
            $before_discount = $price;
            $price -= ($price * $product->discount)/100;
        }elseif($product->discount_type == 'flat'){
            $before_discount = $price;
            $price -= $product->discount;
        }

        return array(
                        'discount' => $product->discount,
                        'before_discount' => front_calc_product_currency($before_discount,$product->weight)['as_text'],
                        'price' => front_calc_product_currency($price,$product->weight)['as_text'],
                        'commission' => $comission,
                        'variant' => $str,
                        'available_quantity' => $available_quantity,
                    );

    }

    public function search(Request $request){

        $category = $sub_category = $sub_sub_category = $search = null;

        $title = 'أحدث المنتجات';

        $site_settings = get_site_setting();
        $products = Product::where('website_setting_id',$site_settings->id)->where('published',1); 

        if($request->search != null){ 
            $search = $request->search;
            $products = $products->where('name','like','%' . $search . '%');  
        }

        if($request->category != null){
            $category = Category::where('slug', $request->category)->first();
            if($category){
                $title = $category->name;
                $category = $category->id;
                $products = $products->where('category_id',$category); 
            }
        }
        if($request->sub_category != null){

            $sub_category = SubCategory::where('slug', $request->sub_category)->first();
            if($sub_category){
                $title = $sub_category->name;
                $sub_category = $sub_category->id;
                $products = $products->where('sub_category_id',$sub_category);
            }
        }

        if($request->sub_sub_category != null){  
            $sub_sub_category = SubSubCategory::where('slug', $request->sub_sub_category)->first();
            if($sub_sub_category){
                $title = $sub_sub_category->name;
                $sub_sub_category = $sub_sub_category->id;
                $products = $products->where('sub_sub_category_id',$sub_sub_category);
            }
        }

        $products = $products->orderBy('created_at','desc')->paginate(12);

        return view('frontend.products',compact('products','title','category','sub_category','sub_sub_category','search'));
    } 
}
