<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ProductComplaintRequest;
use App\Http\Requests\Api\V1\Customer\ProductRateRequest;
use App\Http\Requests\Api\V1\Customer\ProductStockReminderRequest;
use App\Http\Requests\Api\V1\Customer\ProductVariantPriceRequest;
use App\Http\Resources\V1\Customer\ProductListResource;
use App\Http\Resources\V1\Customer\ProductReviewResource;
use App\Http\Resources\V1\Customer\ProductSingleResource;
use App\Http\ResponseHelper;
use App\Models\Product;
use App\Models\ProductComplaint;
use App\Models\ProductReview;
use App\Models\ProductStockRemember;
use App\Models\Review;
use App\Models\Store;
use App\Utils\NotificationUtility;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function detail($productId)
    {
        $product = Product::findOrFail($productId);
        return ResponseHelper::returnResource(ProductSingleResource::make($product));
    }

    public function relatedProducts($productId)
    {
        $product = Product::findOrFail($productId);

        $related_products = Product::with('category')
            ->where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $product->id)
            ->where('published', '1')
            ->take(10)
            ->get();
        return ResponseHelper::returnResource(ProductListResource::collection($related_products));
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
                'base_price' => round($productStock->basePrice($product)),
                'discounted_price' => round($productStock->baseDiscountedPrice($product)),
                'product_stock_id' => $productStock->id,
            ]);
        } else {
            return ResponseHelper::returnNotProcessed(trans('api.errors.productDoesntHaveVariation'));
        }
    }

    public function reviews($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(25);

        return ResponseHelper::returnResource(ProductReviewResource::collection($reviews));
    }
}
