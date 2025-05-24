<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthResponse
{

    public static function success(string $message, array $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public static function error(string $message, array $details = null, int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'details' => $details
        ], $code);
    }

    public static function withCookies(string $message, array $data, array $cookies): JsonResponse
    {
        $response = self::success($message, $data);
        foreach ($cookies as $cookie) {
            $response->withCookie($cookie);
        }
        return $response;
    }
}
