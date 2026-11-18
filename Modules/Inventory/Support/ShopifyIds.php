<?php

namespace Modules\Inventory\Support;

class ShopifyIds
{
    public static function numeric(?string $id): ?string
    {
        if ($id === null) {
            return null;
        }

        $id = trim($id);
        if ($id === '') {
            return null;
        }

        if (str_contains($id, '/')) {
            return substr($id, strrpos($id, '/') + 1);
        }

        return $id;
    }

    public static function gid(?string $id, string $type): ?string
    {
        $numeric = self::numeric($id);
        if ($numeric === null) {
            return null;
        }

        return 'gid://shopify/'.$type.'/'.$numeric;
    }
}
