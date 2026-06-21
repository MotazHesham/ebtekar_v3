<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\ProductReviewLikeRequest;
use App\Http\Resources\V1\Customer\ProductReviewResource;
use App\Http\ResponseHelper;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;

class ProductReviewController extends Controller
{
    public function reviews($productId)
    {
        $productReviews = ProductReview::where('product_id', $productId)
            ->with('user.media')
            ->paginate(15);

        return ResponseHelper::returnResource(ProductReviewResource::collection($productReviews));
    }

    public function toggleLike(ProductReviewLikeRequest $request): JsonResponse
    {
        $review = ProductReview::findOrFail($request->review_id);
        $user = auth()->user();

        if ($review->likes()->where('user_id', $user->id)->exists()) {
            $review->likes()->detach($user->id);
            $review->decrement('likes_count');
            $message = trans('api.success.reviewUnliked');
        } else {
            $review->likes()->attach($user->id);
            $review->increment('likes_count');
            $message = trans('api.success.reviewLiked');
        }

        return ResponseHelper::returnResponse($message, [
            'likesCount' => $review->fresh()->likes_count,
        ]);
    }
}
