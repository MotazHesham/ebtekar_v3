<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function wishlist(){
        $wishlists = Wishlist::where('user_id',Auth::id())->orderBy('created_at','desc')->paginate(10);
        return view('frontend.wishlist',compact('wishlists'));
    }

    public function add($slug){
        $product = Product::where('slug',$slug)->first();
        Wishlist::firstOrCreate([
            'product_id' => $product->id,
            'user_id' => Auth::id()
        ]);
        toast('Product Added To Wishlist','success');
        return redirect()->back();
    }

    public function delete(Request $request){
        $wishlist = Wishlist::find($request->id);
        $wishlist->delete();
        return 1;
    }
}
