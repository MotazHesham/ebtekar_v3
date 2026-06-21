<?php

namespace App\Services;

use App\Exceptions\UserFriendlyException;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CommissionHistory;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\StanderOrder;
use App\Models\User;
use App\Utils\NotificationUtility;
use App\Utils\AddressUtility;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected PaymentService $paymentService,
        protected OrderSummaryService $orderSummaryService,
        protected StockService $stockService,
    ) {}

    public function checkStockAvailability($cartItems)
    {
        foreach ($cartItems as $cartItem) {
            $this->stockService->checkAvailability($cartItem->product, $cartItem->product_stock_id, $cartItem->quantity);
        }
    }

    public function createOrder(Cart $cart, Collection $cartItems): Order
    {
        $site_settings = get_site_setting();
        $address = Address::findOrFail($cart->address_id);
        $defaultCurrency = Currency::where('code', 'EG')->first();
        $country = Country::findOrFail($address->country_id);

        if (!$cart->user_id) {
            $user = $this->createUser($address);
            $cart->user_id = $user->id;
            $cart->save();
        } else {
            $user = $cart->user;
        }

        $cartSummary = $this->orderSummaryService->cartSummary($cart);

        $order_num = generateOrderNumber('customer-app#', $site_settings->id);
        $order = Order::create([
            'order_num' => $order_num,
            'user_id' => $user->id,
            'shipping_country_id'  => $country->id,
            'shipping_country_cost' => $country->cost,
            'website_setting_id' => $site_settings->id,
            'phone_number' => $address->phone,
            'shipping_address' => $address->address,
            'symbol' => $defaultCurrency->symbol,
            'exchange_rate' => $defaultCurrency->exchange_rate,
            'order_type' => 'customer',
        ]);

        $orderDetails = [];
        $productIds = [];
        $total_cost = 0;
        $numOfItems = 0;

        foreach ($cartItems as $cartItem) {
            $numOfItems += $cartItem->quantity;
            $productIds[] = $cartItem->product_id;

            // increse number of sales in product
            $product = Product::findOrFail($cartItem->product_id);
            $product->num_of_sale += $cartItem->quantity;
            $product->save();
            $weight = $product->weight;

            if ($product->variant_product == 1 && $cartItem->variant != null) {
                //remove requested quantity from stock
                $product_stock = $product->stocks()->where('variant', $cartItem->variant)->first();
                if ($product_stock) {
                    $product_stock->stock -= $cartItem->quantity;
                    $product_stock->save();
                }
            } else {
                //remove requested quantity from stock
                $product->current_stock -= $cartItem->quantity;
                $product->save();
            }

            $price = $cartItem->baseDiscountedCartPrice();
            $orderDetails[] = [
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_stock_id' => $cartItem->product_stock_id,
                'price' => $price * $cartItem->quantity,
                'variation' => $cartItem->variant,
                'quantity' => $cartItem->quantity,
                'description' => $cartItem->description,
                'photos' => $cartItem->photos,
                'pdf' => $cartItem->pdf,
                'link' => $cartItem->link,
                'email_sent' => $cartItem->email_sent,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        OrderDetail::insert($orderDetails);

        $order = $order->fresh();
        return $order;
    }
    public function createUser(Address $address): User
    {
        $user = User::create([
            'phone_number' => $address->phone,
            'user_type' => 'customer',
            'approved' => 1,
            'website_setting_id' => get_site_setting()->id,
        ]);
        Customer::create([
            'user_id' => $user->id
        ]);
        return $user;
    }

    public function pay($order, $paymentMethodId)
    {
        if ($order->order_status != 'store_approved') {
            throw new UserFriendlyException(trans('api.errors.storeNotApprovedYet'));
        } else if ($order->payment_status == 'paid') {
            throw new UserFriendlyException(trans('api.errors.alreadyPaid'));
        }
        return DB::transaction(function () use ($order, $paymentMethodId) {
            $paymentMethod = PaymentMethod::findOrFail($paymentMethodId);
            $order->payment_method = strtolower($paymentMethod->name);
            $order->save();
            return $this->paymentService->processPayment($paymentMethod, 'order', $order->id);
        });
    }
}
