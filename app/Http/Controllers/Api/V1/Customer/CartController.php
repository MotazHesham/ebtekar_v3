<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\AddToCartRequest;
use App\Http\Requests\Api\V1\Customer\CheckoutRequest;
use App\Http\Requests\Api\V1\Customer\UpdateCartQuantityRequest;
use App\Http\Requests\Api\V1\Customer\UpdateCartRequest;
use App\Http\Resources\V1\Customer\CartItemResource;
use App\Http\Resources\V1\Customer\CartResource;
use App\Http\ResponseHelper;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use App\Services\AddressService;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected AddressService $addressService,
    ) {}


    public function add(AddToCartRequest $request)
    {
        $cart = $this->cartService->getCart();
        $requestedQuantity = $request->quantity;

        $product = Product::findOrFail($request->product_id);

        if ($product->variant_product) {
            if (!$request->product_stock_id) {
                return ResponseHelper::returnNotProcessed(
                    trans('api.errors.productStockIdRequired'),
                );
            }
        }

        $photos = array();
        if ($request->has('photos')) {
            foreach ($request->photos as $key => $photo) {
                $photos[$key]['photo'] = $photo->store('uploads/orders/products/photos');
                $photos[$key]['note'] = $request->photos_note[$key] ?? '';
            }
        }

        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'product_stock_id' => $request->product_stock_id,
            'description' => $request->description,
            'photos' => json_encode($photos),
        ]);

        if ($cartItem->exists) {
            $requestedQuantity += $cartItem->quantity;
        }

        $productStock = $this->cartService->checkStockAvailability($product, $request->product_stock_id, $requestedQuantity);

        $cart->update([
            'updated_at' => now(),
        ]);

        $cartItem->variant = $productStock->variant ?? null;
        $cartItem->quantity = $requestedQuantity;
        $cartItem->save();

        return ResponseHelper::returnResponse(trans('api.success.addedToCart'), [
            'id' => $cart->id,
            'temp_user_uid' => $cart->temp_user_uid,
            'user_id' => $cart->user_id,
        ]);
    }

    public function view()
    {
        $cart = $this->cartService->getCart();
        return ResponseHelper::returnResource(new CartResource($cart));
    }

    public function items()
    {
        $cart = $this->cartService->getCart();

        $cartItems = $cart->cartItems()->with('product', 'productStock')->get();

        return ResponseHelper::returnResource(CartItemResource::collection($cartItems));
    }

    public function updateCart(UpdateCartRequest $request)
    {
        $cart = $this->cartService->getCart();

        $cart->note = $request->note;

        if ($request->address_id) {
            $shippingCost = $this->addressService->getShippingCost($request->address_id);
            $cart->address_id = $request->address_id;
            $cart->shipping_cost = $shippingCost;
        }

        if ($request->coupon_code && $request->coupon_code != null) {
            return ResponseHelper::returnNotProcessed(
                trans('api.errors.couponNotFound'),
            );
        }

        $cart->save();

        return ResponseHelper::returnResource(new CartResource($cart), trans('api.success.updatedCart'));
    }

    public function updateQuantity(UpdateCartQuantityRequest $request)
    {
        $cart = $this->cartService->getCart();
        $cartItem = CartItem::find($request->cart_item_id);
        $this->cartService->authorizeToUpdate($cartItem, $cart->user_id);
        $this->cartService->checkStockAvailability($cartItem->product, $cartItem->product_stock_id, $request->quantity);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $cart->updated_at = now();
        $cart->save();
        return ResponseHelper::returnResource(
            new CartResource($cart),
            trans('api.success.updatedQuantity')
        );
    }

    public function removeItem($cartItemId)
    {
        $cart = $this->cartService->getCart();
        $cartItem = CartItem::findOrFail($cartItemId);
        $this->cartService->authorizeToUpdate($cartItem, $cart->user_id);
        $cartItem->delete();

        $cart->updated_at = now();
        $cart->save();
        return ResponseHelper::returnResource(
            new CartResource($cart),
            trans('api.success.removedItem')
        );
    }

    public function removeAllItems()
    {
        $cart = $this->cartService->getCart();
        $cart->cartItems()->delete();
        $cart->update(['address_id' => null, 'note' => null, 'coupon_code' => null]);
        return ResponseHelper::returnResource(
            new CartResource($cart),
            trans('api.success.removedAllItems')
        );
    }

    public function checkout(CheckoutRequest $request)
    {
        return $this->cartService->checkout($request->payment_method);
    }
}
