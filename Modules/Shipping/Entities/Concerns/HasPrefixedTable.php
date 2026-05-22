<?php

namespace Modules\Shipping\Entities\Concerns;

use Modules\Shipping\Support\ShippingTables;

trait HasPrefixedTable
{
    public function getTable(): string
    {
        return ShippingTables::name(static::shippingTableBase());
    }

    protected static function shippingTableBase(): string
    {
        if (property_exists(static::class, 'shippingTableBase')) {
            return static::$shippingTableBase;
        }

        throw new \LogicException(static::class . ' must define protected static string $shippingTableBase');
    }
}
