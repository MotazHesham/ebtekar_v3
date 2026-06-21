<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\Resources\V1\Customer\StoreResource;
use App\Http\ResponseHelper;
use App\Models\Product;
use App\Models\Search;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchProduct()
    {

        $products = Product::active()->with('store');

        if ($userSearch = getRequestHelper('search')) {
            Search::updateOrCreate(
                ['query' => $userSearch],
                ['count' => DB::raw('count + 1')]
            );
            $products->where(function ($q) use ($userSearch) {
                foreach (explode(' ', trim($userSearch)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        }

        if ($categoryId = getRequestHelper('category_id')) {
            $products->whereHas('product_categories', function ($q) use ($categoryId) {
                $q->where('product_category_id', $categoryId);
            });
        }

        if (getRequestHelper('min_price') && getRequestHelper('max_price')) {
            $products->where('unit_price', '>=', getRequestHelper('min_price'))
                ->where('unit_price', '<=', getRequestHelper('max_price'));
        }

        if ($colors = getRequestHelper('colors')) {
            $products->where(function ($query) use ($colors) {
                foreach ($colors as $color) {
                    $str = '"#' . $color . '"';
                    $query->orWhere('colors', 'like', '%' . $str . '%');
                }
            });
        }

        if ($attributeValues = getRequestHelper('attribute_values')) {
            $products->where(function ($query) use ($attributeValues) {
                foreach ($attributeValues as $attributeValue) {
                    $str = '"' . $attributeValue . '"';

                    $query->orWhere('choice_options', 'like', '%' . $str . '%');
                }
            });
        }

        switch (getRequestHelper('sort_by', get_setting('default_sorting_products'))) {
            case 'top-rated':
                $products->orderBy('rating', 'desc');
                break;
            case 'low-rate':
                $products->orderBy('rating', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            case 'num_of_sale';
                $products->orderBy('num_of_sale', 'desc');
                break;
            case 'latest':
                $products->orderBy('created_at', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
                break;
        }
        $products = $products->paginate(10);
        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }

    public function searchStore()
    {
        $stores = Store::verified();
        if ($userSearch = getRequestHelper('search')) {
            $stores->where('store_name', 'like', '%' . $userSearch . '%');
        }

        if ($storeType = getRequestHelper('store_type')) {
            $stores->where('store_type', $storeType);
        }

        if ($brands = getRequestHelper('brands')) {
            $stores->whereHas('products', function ($q) use ($brands) {
                $q->whereIn('brand_id', $brands);
            });
        }
        $stores = $stores->paginate(10);
        return ResponseHelper::returnResource(StoreResource::collection($stores));
    }
}
