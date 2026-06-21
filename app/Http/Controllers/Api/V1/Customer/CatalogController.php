<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\Resources\V1\Customer\CategoryResource;
use App\Http\ResponseHelper;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{

    public function products()
    {
        $products = Product::where('published', 1);

        if (request()->has('search')) {
            $products->where(function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%')
                    ->orWhere('description', 'like', '%' . request()->search . '%');
            });
        }

        if (request()->has('category_id')) {
            $products->where('category_id', request()->category_id);
        }

        if (request()->has('min_price')) {
            $products->where('unit_price', '>=', request()->min_price);
        }

        if (request()->has('max_price')) {
            $products->where('unit_price', '<=', request()->max_price);
        }

        if (request()->has('best_selling') && request()->best_selling == 1) {
            $products->where('num_of_sale', '>', 0)->orderBy('num_of_sale', 'desc');
        }

        if (request()->has('sort')) {
            switch (request()->sort) {
                case 'price_asc':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price_desc':
                    $products->orderBy('unit_price', 'desc');
                    break;
                case 'rating_desc':
                    $products->orderBy('rating', 'desc');
                    break;
            }
        }
        $products = $products->orderBy('created_at', 'desc')->cursorPaginate(12);
        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }
    public function categories()
    {
        $categories = Category::where('published', 1)->get();
        return ResponseHelper::returnResource(CategoryResource::collection($categories));
    }
}
