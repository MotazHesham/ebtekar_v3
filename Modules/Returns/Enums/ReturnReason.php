<?php

namespace Modules\Returns\Enums;

enum ReturnReason: string
{
    case CustomerUnavailable = 'customer_unavailable';
    case CustomerRefused     = 'customer_refused';
    case WrongNumber         = 'wrong_number';
    case UnclearAddress      = 'unclear_address';
    case ShippingDelay       = 'shipping_delay';
    case NoAnswer            = 'no_answer';
    case Other               = 'other';

    public static function labels(): array
    {
        return [
            self::CustomerUnavailable->value => 'Customer Unavailable',
            self::CustomerRefused->value     => 'Customer Refused',
            self::WrongNumber->value         => 'Wrong Number',
            self::UnclearAddress->value      => 'Unclear Address',
            self::ShippingDelay->value       => 'Shipping Delay',
            self::NoAnswer->value            => 'No Answer',
            self::Other->value               => 'Other',
        ];
    }
}
