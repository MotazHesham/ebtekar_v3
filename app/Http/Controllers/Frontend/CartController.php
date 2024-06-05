<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){ 
        return view('frontend.cart');
    }

    public function add(Request $request){
        
        $product = Product::findOrFail($request->id); 
        $data = array(); 
        $data['id'] = $product->id . '-' . $request->variant;
        $data['product_id'] = $product->id;
        $data['variation'] = $request->variant;
        $data['description'] = $request->description;
        $data['quantity'] = $request->quantity ?? 1;    
        $data['email_sent'] = $request->file_sent == 'on' ? 1 : 0;
        $data['link'] = $request->link ?? null;  

        if($request->hasFile('pdf')){
            $data['pdf'] = $request->pdf->store('uploads/orders/products/pdf');
        } 
        $photos = array();  
        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $photos[$key]['photo'] = $photo->store('uploads/orders/products/photos');
                $photos[$key]['note'] = $request->photos_note[$key] ?? '';  
            }
        } 
        $data['photos'] = json_encode($photos);
        
        if(session('cart')){
            $foundInCart = false;
            $cart = collect();

            foreach (session('cart') as $cartItem){
                if($cartItem['id'] == $product->id . '-' . $request->variant){ 
                    $foundInCart = true;
                    $cartItem['quantity'] += ($request['quantity'] ?? 1); 
                }
                $cart->push($cartItem);
            }

            if (!$foundInCart) {
                $cart->push($data);
            } 
            session()->put('cart', $cart);
        } else{
            $cart = collect([$data]);
            session()->put('cart', $cart);
        }

        toast('Success added to cart','success');
        
        if($request->has('buy_now')){
            return redirect()->route('frontend.payment_select');
        }else{
            return redirect()->back()->with(['open_cart'=>true]);
        }
    }


    public function edit(Request $request){
        $product  = Product::findOrFail($request->id);
        $cart = session('cart')->where('id',$request->id)->first();
        return view('frontend.partials.edit_cart',compact('product','cart'));
    }

    public function update(Request $request){
        // return $request;
        
        $total = $cartIteam_total = 0;
        
        $cart = collect();
        foreach(session('cart') as $cartItem){ 

            $product_stock =  ProductStock::where('variant', $cartItem['variation'])->where('product_id',$cartItem['product_id'])->first();
            $product = Product::find($cartItem['product_id']);
            if($product_stock){ 
                $price = front_calc_product_currency($product->calc_discount($product_stock->unit_price),$product->weight);
                $commission = front_calc_commission_currency($product_stock->unit_price,$product_stock->purchase_price);
            }else{ 
                $price = front_calc_product_currency($product->calc_discount($product->unit_price),$product->weight);
                $commission = front_calc_commission_currency($product->unit_price,$product->purchase_price);
            } 

            if($cartItem['id'] == $request->id){
                $cartItem['quantity'] = $request->quantity;
                $cartIteam_total = ($price['value'] * $cartItem['quantity'] );
                $cartIteam_commission = ($commission['value'] * $cartItem['quantity'] );

                // when editing the photo or description
                if(!$request->ajax()){
                    $photos = array();  
                    if($request->has('previous_photos')){
                        foreach ($request->previous_photos as $key => $prev) {
                            $photos[]['photo'] = $prev;  
                        }
                    }
                    if($request->hasFile('photos')){
                        foreach ($request->photos as $key => $photo) {
                            $photos[]['photo'] = $photo->store('uploads/orders/products/photos');  
                        }
                    }  
                    if($request->has('photos_note')){ 
                        foreach ($request->photos_note as $key2 => $note) {  
                            $photos[$key2]['note'] = $note ?? '';  
                        } 
                    }  
                    $cartItem['description'] = $request->description;
                    $cartItem['photos'] = json_encode($photos); 
                }
            }
            $cart->push($cartItem);
            session()->put('cart', $cart);

            $total += ($price['value'] * $cartItem['quantity'] );
        }

        if($request->ajax()){
            return [ 
                'total_cost' => $total . $price['symbol'],
                'cartIteam_total' => $cartIteam_total . $price['symbol'],
                'cartIteam_commission' => $cartIteam_commission . $price['symbol'],
            ];
        }else{
            alert('تم التعديل بنجاح','','success');
            return redirect()->back()->with(['open_cart'=>true]);
        }

    }

    public function delete(Request $request){
        if(session()->has('cart')){ 
            $cart = session('cart');
            $cart = $cart->where('id','!=',$request->id);
            session()->put('cart',$cart); 
        }
        toast('Success Removed item to cart','success');
        return redirect()->back()->with(['open_cart'=>true]);
    }
}
