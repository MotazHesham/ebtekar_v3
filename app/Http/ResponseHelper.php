<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseHelper
{
    public static function returnResource(JsonResource $resource, string $message = '', bool $status = true, $code = '200'): JsonResource
    {
        return $resource->additional([
            'message' => $message,
            'status' => $status,
            'code' => $code,
        ]);
    }

    public static function returnResponse(string $message, $data = null, bool $status = true, $code = '200'): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
            'code' => $code,
        ], $code == '200' ? 200 : 500);
    }

    public static function returnNotFound(string $message): JsonResponse
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'status' => false,
            'code' => '004',
        ], 404);
    }


    public static function returnNotProcessed(string $message, $data = null): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => false,
            'code' => '001',
        ], 500);
    }
}
