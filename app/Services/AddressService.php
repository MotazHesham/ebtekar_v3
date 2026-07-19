<?php

namespace App\Services;

use App\Exceptions\UserFriendlyException;
use App\Models\Address;
use App\Models\StoreCity;

class AddressService
{
    public function getShippingCost($addressId)
    {
        $address = Address::findOrFail($addressId);
        $country = $address->country;
        if ($country) {
            return $country->cost;
        }
        return 0;
    }
}
