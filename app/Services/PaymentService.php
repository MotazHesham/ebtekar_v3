<?php

namespace App\Services;

use App\Models\PaymentMethod;

class PaymentService
{
    public function processPayment(PaymentMethod $paymentMethod, string $paymentType, int $paymentId)
    {
        $decorator = $this->getPaymentDecorator($paymentMethod->key);
        return (new $decorator)->pay($paymentType, $paymentId);
    }

    protected function getPaymentDecorator(string $method): string
    {
        $className = 'App\\Http\\Controllers\\Api\\V1\\Payment\\' .
            str_replace(' ', '', ucwords(str_replace('_', ' ', $method))) . 'Controller';

        if (!class_exists($className)) {
            throw new \Exception("Payment method $method not supported");
        }

        return $className;
    }
}
