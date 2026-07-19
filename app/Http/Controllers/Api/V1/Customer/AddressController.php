<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\AddressStoreRequest;
use App\Http\Requests\Api\V1\Customer\AddressUpdateRequest;
use App\Http\Resources\V1\Customer\AddressResource;
use App\Http\ResponseHelper;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function list()
    {
        if ($user = getAuthUser()) {
            $addresses = Address::where('user_id', $user->id)
                ->with('country')
                ->get();
        } else {
            $addresses = Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))
                ->with('country')
                ->get();
        }
        return ResponseHelper::returnResource(AddressResource::collection($addresses));
    }
    public function add(AddressStoreRequest $request)
    {
        $validatedRequest = $request->validated();
        if ($user = getAuthUser()) {
            $validatedRequest['user_id'] = $user->id;
        } else {
            $validatedRequest['temp_user_uid'] = getRequestHelper('temp_user_uid');
        }
        $address = Address::create($validatedRequest);

        if ($request->is_default) {
            if ($user = getAuthUser()) {
                Address::where('user_id', $user->id)->where('id', '!=', $address->id)->update(['is_default' => false]);
            } else {
                Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->where('id', '!=', $address->id)->update(['is_default' => false]);
            }
        } else {
            $user->defaultUserAddress();
        }
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }

    public function update(AddressUpdateRequest $request)
    {
        $validatedRequest = $request->validated();
        if ($user = getAuthUser()) {
            $validatedRequest['user_id'] = $user->id;
            $address = Address::where('user_id', $user->id)->findOrFail($request->address_id);
        } else {
            $validatedRequest['temp_user_uid'] = getRequestHelper('temp_user_uid');
            $address = Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->findOrFail($request->address_id);
        }
        $address->update($validatedRequest);

        if ($request->is_default) {
            if ($user = getAuthUser()) {
                Address::where('user_id', $user->id)->where('id', '!=', $address->id)->update(['is_default' => false]);
            } else {
                Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->where('id', '!=', $address->id)->update(['is_default' => false]);
            }
        }
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
    public function delete($addressId)
    {
        if ($user = getAuthUser()) {
            $address = Address::where('user_id', $user->id)->findOrFail($addressId);
        } else {
            $address = Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->findOrFail($addressId);
        }
        $address->delete();
        if ($user = getAuthUser()) {
            $user->defaultUserAddress();
        }
        return ResponseHelper::returnResponse(trans('api.success.deleted'));
    }

    public function setDefault($addressId)
    {
        if ($user = getAuthUser()) {
            $address = Address::where('user_id', $user->id)->findOrFail($addressId);
        } else {
            $address = Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->findOrFail($addressId);
        }
        $address->update(['is_default' => true]);

        if ($user = getAuthUser()) {
            Address::where('user_id', $user->id)->where('id', '!=', $addressId)->update(['is_default' => false]);
        } else {
            Address::where('temp_user_uid', getRequestHelper('temp_user_uid'))->where('id', '!=', $addressId)->update(['is_default' => false]);
        }

        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
}
