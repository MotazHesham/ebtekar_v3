<?php

namespace App\Services;

use App\Utils\CouponUtility;

class OrderSummaryService
{
    public function cartSummary($cart)
    {
        $total = 0;
        $subTotal = $cart->calculatedSubTotal();
        $totalDiscountedPrice = $this->totalDiscountedPrice($subTotal, $cart->coupon_code, $cart->user_id);

        $discountedAmount = $subTotal - $totalDiscountedPrice;

        $total = $discountedAmount + $cart->shipping_cost;
        return [
            'sub_total' => round($subTotal),
            'shipping_cost' => round($cart->shipping_cost),
            'discount' => round($totalDiscountedPrice),
            'total' => round($total),
        ];
    }
    public function recalculateOrderSummary($order, $couponCode)
    {
        $total = 0;
        $subTotal = $order->calculatedSubTotal();
        $totalDiscountedPrice = $this->totalDiscountedPrice($subTotal, $couponCode, $order->user_id);

        $discountedAmount = $subTotal - $totalDiscountedPrice;

        $total = $discountedAmount + $order->shipping_cost;
        return [
            'sub_total' => $subTotal,
            'shipping_cost' => $order->shipping_cost,
            'discount' => $totalDiscountedPrice,
            'total' => $total,
        ];
    }


    private function totalDiscountedPrice($totalPrice, $couponCode, $userId)
    {
        $totalDiscountedPrice = 0;
        if ($couponCode) {
            // $couponUtility = new CouponUtility();
            // $totalDiscountedPrice = $couponUtility->calculateDiscount(
            //     $couponCode, 
            //     $userId,
            //     $totalPrice
            // );
        }
        return $totalDiscountedPrice;
    }
}
