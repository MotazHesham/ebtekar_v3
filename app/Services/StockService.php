<?php

namespace App\Services;

use App\Exceptions\UserFriendlyException;

class StockService
{
    public function checkAvailability($product, $productStockId, $quantity)
    {
        $productStock = null;

        if ($product->variant_product) {
            $productStock = $product->stocks()->where('id', $productStockId)->first();
            if (!$productStock) {
                throw new \Exception('Variant not found');
            }
            if ($productStock->stock < $quantity) {
                throw new UserFriendlyException(trans('api.errors.quantityNotAvailable'), ['product_id' => $product->id]);
            }
        } else {
            if ($product->current_stock < $quantity) {
                throw new UserFriendlyException(trans('api.errors.quantityNotAvailable'), ['product_id' => $product->id]);
            }
        }

        return $productStock;
    }
}
