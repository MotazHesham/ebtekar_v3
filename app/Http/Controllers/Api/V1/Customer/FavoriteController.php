<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ProductFavoriteRequest;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\ResponseHelper;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;

class FavoriteController extends Controller
{
    public function list()
    {
        $favs = Cache::remember('customer-' . auth()->user()->id . '-fav-products', config('panel.cache_time_long'), function () {
            return Wishlist::where('user_id', auth()->user()->id)->get()->pluck('product_id')->toArray();
        });
        $products = Product::whereIn('id', $favs)->orderBy('num_of_sale', 'desc')->paginate(10);
        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }
    public function toggle(ProductFavoriteRequest $request)
    {
        $product = Product::find($request->product_id);

        $user = auth()->user();

        if ($product->wishlists()->where('user_id', $user->id)->exists()) {
            $product->wishlists()->detach($user->id);
            Cache::forget('customer-' . $user->id . '-fav-products');
            return ResponseHelper::returnResponse(trans('api.success.removedFromFavorites'));
        } else {
            $product->wishlists()->attach($user->id);
            Cache::forget('customer-' . $user->id . '-fav-products');
            return ResponseHelper::returnResponse(trans('api.success.addedToFavorites'));
        }
    }
}
