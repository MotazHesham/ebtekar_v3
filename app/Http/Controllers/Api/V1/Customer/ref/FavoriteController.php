<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ProductFavoriteRequest;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\ResponseHelper;
use App\Models\Product;
use App\Models\ProductFavorite; 
use Illuminate\Support\Facades\Cache;

class FavoriteController extends Controller
{
    public function list()
    {
        $favs = Cache::remember('user-' . auth()->user()->id . '-fav-products', config('panel.cache_time_long'), function () {
            return ProductFavorite::where('user_id', auth()->user()->id)->get()->pluck('product_id')->toArray();
        });
        $products = Product::whereIn('id', $favs)->orderBy('num_of_sale', 'desc')->paginate(10);
        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }
    public function toggle(ProductFavoriteRequest $request)
    {
        $product = Product::find($request->product_id);
        
        $user = auth()->user();
        
        if($product->favorites()->where('user_id', $user->id)->exists()) {
            $product->favorites()->detach($user->id);
            Cache::forget('user-'.$user->id.'-fav-products');
            return ResponseHelper::returnResponse(trans('api.success.removedFromFavorites'));
        } else {
            $product->favorites()->attach($user->id);
            Cache::forget('user-'.$user->id.'-fav-products');
            return ResponseHelper::returnResponse(trans('api.success.addedToFavorites')); 
        }
    }
}
