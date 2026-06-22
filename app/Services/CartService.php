<?php

namespace App\Services;

use App\Exceptions\UserFriendlyException;
use App\Models\Cart;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use App\Services\PaymentService;

class CartService
{
    public function __construct(
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected StockService $stockService,
    ) {}

    public function getCart(): Cart
    {
        if ($user = getAuthUser()) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id], []);
        } elseif (getRequestHelper('temp_user_uid')) {
            $tempUserUid = getRequestHelper('temp_user_uid');
            $cart = Cart::firstOrCreate(['temp_user_uid' => $tempUserUid], []);
        } else {
            do {
                $tempUserUid = bin2hex(random_bytes(10));
            } while (Cart::where('temp_user_uid', $tempUserUid)->exists());
            $cart = Cart::create([
                'temp_user_uid' => $tempUserUid,
            ]);
        }

        $cart->load('cartItems', 'cartItems.product', 'cartItems.productStock');
        return $cart;
    }

    public function checkStockAvailability($product, $productStockId, $quantity)
    {
        return $this->stockService->checkAvailability($product, $productStockId, $quantity);
    }

    public function emptyCart($cart): Cart
    {
        $cart->cartItems()->delete();
        $cart->update(['address_id' => null, 'note' => null, 'coupon_code' => null]);
        return $cart;
    }

    public function authorizeToUpdate($cartItem, $userId)
    {
        if ($cartItem->cart->user_id != $userId) {
            throw new UnauthorizedException(trans('api.errors.unauthorized'));
        }
    }

    public function checkout($paymentMethodKey)
    {
        return DB::transaction(function () use ($paymentMethodKey) {
            $cart = $this->getCart();
            $cartItems = $cart->cartItems()->with('productStock', 'product')->get();

            throw_if($cartItems->isEmpty(), UserFriendlyException::class, trans('api.errors.cartIsEmpty'));

            $this->orderService->checkStockAvailability($cartItems);

            $order = $this->orderService->createOrder($cart, $cartItems);
            $this->emptyCart($cart);

            $paymentMethod = PaymentMethod::where('key', $paymentMethodKey)->first();
            $order->payment_type = $paymentMethod->name;
            $order->save();
            return $this->paymentService->processPayment($paymentMethod, 'order', $order->id);
        });
    }
}
