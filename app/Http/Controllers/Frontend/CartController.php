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
        $cart = Cart::with('product')->where('user_id',Auth::id())->orderBy('created_at','desc')->paginate(10);
        return view('frontend.cart',compact('cart'));
    }

    public function add(Request $request){

        $product = Product::findOrFail($request->id);

        if($product->variant_product == 1){

            $product_stock = ProductStock::where('variant', $request->variant)->first();

            $commission = ($product_stock->unit_price  - $product_stock->purchase_price) * $request->quantity;

            $price = $product->discount > 0 ? $product->calc_discount($product_stock->unit_price) : $product_stock->unit_price;

        }else {

            $commission = ($product->unit_price  - $product->purchase_price) * $request->quantity;

            $price = $product->discount > 0 ? $product->calc_discount($product->unit_price) : $product->unit_price;

        }

        $cart = new Cart();
        $cart->user_id  = Auth::id();
        $cart->product_id = $product->id;
        $cart->variation = $request->variant;
        $cart->description = $request->description;
        $cart->quantity = $request->quantity;
        $cart->price = $price;
        $cart->total_cost = $price * $request->quantity;

        if(Auth::user()->user_type == 'seller'){
            $cart->commission = $commission;
            $cart->email_sent = $request->file_sent == 'on' ? 1 : 0;
            $cart->link = $request->link;
        }else{
            $cart->commission = $commission;
            $cart->email_sent = 0;
            $cart->link = null;
        }

        if($request->hasFile('pdf')){
            $cart->pdf = $request->pdf->store('uploads/seller/products/pdf');
        }

        $photos = array();
        $photos_note = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/seller/products/photos');
                array_push($photos, $path);
            }
            $cart->photos = json_encode($photos);
        }

        if($request->has('photos_note')){
            foreach ($request->photos_note as $key => $note) {
                array_push($photos_note, $note);
            }

            $cart->photos_note = json_encode($photos_note);
        }

        $cart->save();

        toast('Success Added To Cart','success');
        return back();
    }

    public function update(Request $request){
        $cart = Cart::findOrFail($request->id);
        $cart->quantity = $request->quantity;
        $cart->total_cost = $request->quantity * $cart->price;
        $cart->save();
        return [
            'total_cost' => front_currency(Cart::where('user_id',$cart->user_id)->get()->sum('total_cost')),
            'cartIteam_total' => front_currency($cart->total_cost)
        ];
    }

    public function delete(Request $request){
        $cart = Cart::findOrFail($request->id);
        $cart->delete();
        return front_currency(Cart::where('user_id',$cart->user_id)->get()->sum('total_cost'));
    }
}
