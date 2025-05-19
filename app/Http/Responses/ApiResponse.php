<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

readonly class ApiResponse
{
    private function __construct(
        private bool $success,
        private string $message,
        private mixed $data = null,
        private ?array $errors = null,
        private int $statusCode = 200
    ) {}

    public static function success(mixed $data = null, string $message = 'Operación exitosa', int $statusCode = 200): JsonResponse
    {
        $response = new self(
            success: true,
            message: $message,
            data: $data,
            statusCode: $statusCode
        );

        return response()->json([
            'success' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], $response->statusCode);
    }

    public static function error(string $message = 'Ha ocurrido un error', ?array $errors = null, int $statusCode = 400): JsonResponse
    {
        $response = new self(
            success: false,
            message: $message,
            errors: $errors,
            statusCode: $statusCode
        );

        return response()->json([
            'success' => $response->success,
            'message' => $response->message,
            'errors' => $response->errors
        ], $response->statusCode);
    }
}
