<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    protected function success(?string $message, $data, $statusCode = 200) : JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function created(?string $message, $data) : JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => 201
        ], 201);
    }

    protected function error(string $message, $statusCode) : JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
