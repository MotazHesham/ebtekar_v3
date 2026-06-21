<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ProductComplaintRequest;
use App\Http\Requests\Api\V1\Customer\ProductRateRequest;
use App\Http\Requests\Api\V1\Customer\ProductStockReminderRequest;
use App\Http\Requests\Api\V1\Customer\ProductVariantPriceRequest;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\Resources\V1\Customer\ProductSingleResource;
use App\Http\ResponseHelper;
use App\Models\Product;
use App\Models\ProductComplaint;
use App\Models\ProductReview;
use App\Models\ProductStockRemember;
use App\Models\Store;
use App\Utils\NotificationUtility;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function bestSellingProducts()
    {
        $products = Cache::remember('best_selling_products', config('panel.cache_time_medium'), function () {
            return Product::active()
                ->with('store', 'media')
                ->orderBy('num_of_sale', 'desc')
                ->take(4)
                ->get();
        });

        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }

    public function productDetail($productId)
    {
        $product = Product::findOrFail($productId);
        return ResponseHelper::returnResource(ProductSingleResource::make($product));
    }

    public function similarProducts($productId)
    {
        $product = Product::findOrFail($productId);
        $similarProducts = Product::active()
            ->where('store_id', $product->store_id)
            ->where('id', '!=', $productId)
            ->take(4)
            ->get();
        return ResponseHelper::returnResource(ProductListResource::collection($similarProducts));
    }


    public function storeProducts($storeId)
    {
        $store = Store::findOrFail($storeId);
        $featuredCategoryIds = $store->featuredCategories()->pluck('id')->toArray();

        $products = Product::active()->where('store_id', $storeId);


        if ($featuredCategoryIds) {
            $products->whereHas('product_categories', function ($q) use ($featuredCategoryIds) {
                $q->whereIn('product_category_id', $featuredCategoryIds);
            });
        }

        if ($categoryId = getRequestHelper('category_id')) {
            $products->whereHas('product_categories', function ($q) use ($categoryId) {
                $q->where('product_category_id', $categoryId);
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
        $products = $products->paginate(15);
        return ResponseHelper::returnResource(ProductListResource::collection($products));
    }

    public function complaint(ProductComplaintRequest $request)
    {
        ProductComplaint::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->user()->id,
            'reason' => $request->complaint,
        ]);
        return ResponseHelper::returnResponse(trans('api.success.complaintSent'));
    }
    public function variantPrice(ProductVariantPriceRequest $request)
    {
        $str = '';
        $product = Product::findOrFail($request->product_id);
        if ($product->variant_product) {

            if ($request->has('color')) {
                $str = get_single_color_name($request['color']);
            }

            if (json_decode($product->attributes) != null) {
                foreach (json_decode($product->attributes) as $attributeId) {
                    $attribute = collect($request['attributes'])->where('attribute_id', $attributeId)->first();
                    if (!$attribute) {
                        return ResponseHelper::returnNotProcessed(trans('api.errors.missingOnOfTheRequiredAttributes'));
                    }

                    $value = str_replace(' ', '', $attribute['value']);
                    if ($str != null) {
                        $str .= '-' . $value;
                    } else {
                        $str .= $value;
                    }
                }
            }

            $productStock = $product->stocks()->where('variant', $str)->first();
            if (!$productStock) {
                throw new \Exception('Variant not found');
            }
            return ResponseHelper::returnResponse('', [
                'max_quantity' => $productStock->stock,
                'price' => $productStock->baseDiscountedPrice(),
                'before_discount' => $productStock->basePrice(),
                'product_stock_id' => $productStock->id,
            ]);
        } else {
            return ResponseHelper::returnNotProcessed(trans('api.errors.productDoesntHaveVariation'));
        }
    }
    public function rate(ProductRateRequest $request)
    {
        $product = Product::find($request->product_id);
        if (!$product->canRate()) {
            return ResponseHelper::returnNotProcessed(trans('api.errors.cannotRate'));
        }
        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->user()->id,
            'rate' => $request->rate,
            'review' => $request->review,
        ]);

        $product->rating = $product->productProductReviews()->avg('rate');
        $product->save();
        return ResponseHelper::returnResponse(trans('api.success.rated'));
    }

    public function stockReminder(ProductStockReminderRequest $request)
    {
        $product = Product::findOrFail($request->id);
        ProductStockRemember::updateOrCreate([
            'product_id' => $request->id,
            'product_stock_id' => $request->product_stock_id,
            'user_id' => auth()->user()->id,
        ], []);

        NotificationUtility::sendProductStockNotification('seller', $product, $request->product_stock_id);

        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
}
