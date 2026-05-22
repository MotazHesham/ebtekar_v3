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

    public function label(): string
    {
        $key = 'returns::reasons.' . $this->value;

        return trans()->has($key) ? __($key) : $this->value;
    }

    public static function labels(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }

        return $out;
    }

    public static function shipmentStatusFor(string $reason): string
    {
        return $reason === self::CustomerRefused->value ? 'refused' : 'returned';
    }
}
