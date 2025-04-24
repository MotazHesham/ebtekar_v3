<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\FacebookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function wishlist(){
        $wishlists = Wishlist::with('product')->where('user_id',Auth::id())->orderBy('created_at','desc')->paginate(10);
        return view('frontend.wishlist',compact('wishlists'));
    }

    public function add($slug){
        $site_settings = get_site_setting();
        $product = Product::where('slug',$slug)->first();
        Wishlist::firstOrCreate([
            'product_id' => $product->id,
            'user_id' => Auth::id()
        ]);
        
        if($site_settings->id == 2){
            $facebookService = new FacebookService();
            $contentData = [
                'event' => 'AddToWishlist',
                'content_name' => $product->name,
                'content_ids' => [(string)$product->id],
                'content_type' => 'product', 
                'value' => is_numeric($product->unit_price) ? (float)$product->unit_price : 0,
                'currency' => 'EGP',
                'content_category' => $product->category->name ?? null
            ];
            session()->flash('eventData', $contentData);
            $facebookService->sendEventFromController( $contentData); 
        }
        toast('Product Added To Wishlist','success');
        return redirect()->back();
    }

    public function delete(Request $request){
        $wishlist = Wishlist::find($request->id);
        $wishlist->delete();
        return 1;
    }
}
