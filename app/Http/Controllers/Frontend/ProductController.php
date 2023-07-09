<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Search;
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
            $comission = front_calc_product_currency($product_stock->unit_price,$product_stock->purchase_price)['as_text'];
            $available_quantity = $product_stock->quantity;
        }else{
            $price = $product->unit_price;
            $comission = front_calc_product_currency($product->unit_price,$product->purchase_price)['as_text'];
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

        $category = $sub_category = $sub_sub_category = $sort_by = $search = null;

        

        $title = 'أحدث المنتجات';

        $site_settings = get_site_setting(); 
        $meta_title = $site_settings->site_name;
        $meta_description = $site_settings->description_seo; 

        $products = Product::where('website_setting_id',$site_settings->id)->where('published',1); 

        if($request->search != null){ 
            $search = $request->search;
            $products = $products->where('name','like','%' . $search . '%');  

            // store the search from the user
            $store_search = Search::where('search', $search)->first();
            if($store_search != null){
                $store_search->count += 1;
                $store_search->save();
            }else{
                $store_search = new Search;
                $store_search->search = $search;
                $store_search->count = 1;
                $store_search->save();
            }
        }

        if($request->category != null){
            $category = Category::where('slug', $request->category)->first();
            if($category){
                $meta_title = $category->meta_title;
                $meta_description = $category->meta_description;

                $title = $category->name;
                $category = $category->id;
                $products = $products->where('category_id',$category); 
            }
        }
        if($request->sub_category != null){

            $sub_category = SubCategory::where('slug', $request->sub_category)->first();
            if($sub_category){
                $meta_title = $sub_category->meta_title;
                $meta_description = $sub_category->meta_description;

                $title = $sub_category->name;
                $sub_category = $sub_category->id;
                $products = $products->where('sub_category_id',$sub_category);
            }
        }

        if($request->sub_sub_category != null){  
            $sub_sub_category = SubSubCategory::where('slug', $request->sub_sub_category)->first();
            if($sub_sub_category){ 
                $meta_title = $sub_sub_category->meta_title;
                $meta_description = $sub_sub_category->meta_description;

                $title = $sub_sub_category->name;
                $sub_sub_category = $sub_sub_category->id;
                $products = $products->where('sub_sub_category_id',$sub_sub_category);
            }
        }

        if($request->sort_by != null){
            $sort_by = $request->sort_by;
            switch ($sort_by) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'price_low':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price_high':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }
        $non_paginate_products = $products->get();

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {  
            foreach (json_decode($product->attributes) as $key => $value) { 
                $flag = false;
                $pos = 0;
                foreach ($attributes as $key => $attribute) {
                    if($attribute['id'] == $value){
                        $flag = true;
                        $pos = $key;
                        break;
                    }
                }
                if(!$flag){
                    $item['id'] = $value;
                    $item['values'] = array();
                    foreach (json_decode($product->attribute_options) as $key => $attribute_option) {
                        if($attribute_option->attribute_id == $value){
                            $item['values'] = $attribute_option->values;
                            break;
                        }
                    }
                    array_push($attributes, $item);
                }else {
                    foreach (json_decode($product->attribute_options) as $key => $attribute_option) {
                        if($attribute_option->attribute_id == $value){
                            foreach ($attribute_option->values as $key => $value) {
                                if(!in_array($value, $attributes[$pos]['values'])){
                                    array_push($attributes[$pos]['values'], $value);
                                }
                            }
                        }
                    }
                }
            } 
        } 

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('attribute_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }

        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_colors = null;

        if($request->has('color')){
            foreach($request->color as $color){
                $str = '"'.$color.'"';
                $products = $products->where('colors', 'like', '%'.$str.'%');
                $selected_colors[] = $color;
            }
        }

        if($request->pagination != null){
            $pagination = $request->pagination;
        }else{
            $pagination = 12;
        }
        $products = $products->orderBy('created_at','desc')->paginate($pagination);

        return view('frontend.products',compact('products','title','category','sub_category','sub_sub_category','search','meta_title','meta_description','attributes','selected_attributes','sort_by','all_colors','selected_colors','pagination'));
    } 
}
