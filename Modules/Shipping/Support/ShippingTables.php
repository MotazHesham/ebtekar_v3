<?php

namespace Modules\Shipping\Support;

use Illuminate\Validation\Rule;

/**
 * Central table names for the shipping modular monolith.
 * Prefixed tables (default sh_) are owned by shipping modules; core app tables stay unprefixed.
 */
final class ShippingTables
{
    public const SHIPPING_PARTNERS = 'shipping_partners';

    public const DELIVERY_ORDERS = 'delivery_orders';

    public const DELIVERY_TIMELINE_EVENTS = 'delivery_timeline_events';

    public const DELIVERY_NOTES = 'delivery_notes';

    public const SHIPMENT_STATUS_HISTORIES = 'shipment_status_histories';

    public const TRACKING_SCANS = 'tracking_scans';

    public const DISPATCH_BATCHES = 'dispatch_batches';

    public const DISPATCH_BATCH_ITEMS = 'dispatch_batch_items';

    public const DELIVERY_SETTLEMENTS = 'delivery_settlements';

    public const COURIER_SETTLEMENT_LINES = 'courier_settlement_lines';

    public const RETURN_CASES = 'return_cases';

    public const NOTIFICATION_DELIVERIES = 'notification_deliveries';

    /** @var list<string> */
    public const PREFIXED = [
        self::SHIPPING_PARTNERS,
        self::DELIVERY_ORDERS,
        self::DELIVERY_TIMELINE_EVENTS,
        self::DELIVERY_NOTES,
        self::SHIPMENT_STATUS_HISTORIES,
        self::TRACKING_SCANS,
        self::DISPATCH_BATCHES,
        self::DISPATCH_BATCH_ITEMS,
        self::DELIVERY_SETTLEMENTS,
        self::COURIER_SETTLEMENT_LINES,
        self::RETURN_CASES,
        self::NOTIFICATION_DELIVERIES,
    ];

    public static function prefix(): string
    {
        return (string) config('shipping.table_prefix', 'sh_');
    }

    public static function name(string $base): string
    {
        $prefix = self::prefix();

        if ($prefix !== '' && str_starts_with($base, $prefix)) {
            return $base;
        }

        return $prefix . $base;
    }

    public static function exists(string $base, string $column = 'id'): string
    {
        return 'exists:' . self::name($base) . ',' . $column;
    }

    public static function unique(string $base, string $column): \Illuminate\Validation\Rules\Unique
    {
        return Rule::unique(self::name($base), $column);
    }
}
