<?php

namespace Modules\Shipping\Enums;

enum ShipmentStatus: string
{
    case Pending               = 'pending';
    case HandedToPartner       = 'handed_to_partner';
    case ReceivedAtWarehouse   = 'received_at_warehouse';
    case OutWithCourier        = 'out_with_courier';
    case CustomerUnavailable   = 'customer_unavailable';
    case Postponed             = 'postponed';
    case Delivered             = 'delivered';
    case Returned              = 'returned';
    case Refused               = 'refused';
    case Closed                = 'closed';
    case Retry                 = 'retry';

    public function timestampColumn(): ?string
    {
        return match ($this) {
            self::HandedToPartner     => 'handed_to_partner_at',
            self::ReceivedAtWarehouse => 'received_by_partner_at',
            self::OutWithCourier      => 'out_with_courier_at',
            self::Delivered           => 'delivered_at',
            self::Returned, self::Refused => 'returned_at',
            default                   => null,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
