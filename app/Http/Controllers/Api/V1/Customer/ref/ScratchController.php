<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ScratchApplyRequest;
use App\Http\Resources\V1\Customer\ScratchResource;
use App\Http\ResponseHelper;
use App\Models\CustomerScratch;
use App\Models\Scratch;
use Illuminate\Http\Request;

class ScratchController extends Controller
{
    public function scratchRandom()
    {
        $scratch = Scratch::inRandomOrder()->first();
        if (!$scratch) {
            return ResponseHelper::returnResponse(trans('api.errors.notFound'));
        }
        return ResponseHelper::returnResource(ScratchResource::make($scratch));
    }

    public function scratchApply(ScratchApplyRequest $request)
    {
        $user = auth()->user();
        if (!$user->customer->can_scratch) {
            return ResponseHelper::returnNotProcessed(trans('api.errors.scratchNotAllowed'));
        }
        $scratch = Scratch::find($request->scratch_id);
        CustomerScratch::create([
            'user_id' => $user->id,
            'scratch_id' => $request->scratch_id,
            'used' => false,
            'expire_at' => now()->addDays($scratch->expiration_days),
        ]);
        $user->customer->can_scratch = false;
        $user->customer->save();
        return ResponseHelper::returnResponse(trans('api.success.success'));
    }
}
