<?php

namespace Modules\Shipping\Enums;

enum ShippingPartnerManagementType: string
{
    case Partner = 'partner';
    case Admin   = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
