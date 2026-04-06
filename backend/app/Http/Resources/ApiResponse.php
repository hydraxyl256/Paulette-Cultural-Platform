<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;

/**
 * Standardized API Response Format
 * 
 * All API responses follow this structure:
 * {
 *   "success": true/false,
 *   "message": "User-friendly message",
 *   "data": {...} or null,
 *   "errors": {...} or null,
 *   "meta": {"timestamp", "request_id"}
 * }
 */
class ApiResponse
{
    /**
     * Success response with data
     */
    public static function success($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID') ?? uniqid('req_'),
            ],
        ], $status);
    }

    /**
     * Error response
     */
    public static function error(string $message, $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID') ?? uniqid('req_'),
            ],
        ], $status);
    }

    /**
     * Validation error response
     */
    public static function validationError(array $errors): JsonResponse
    {
        return static::error('Validation failed', $errors, 422);
    }

    /**
     * Unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return static::error($message, null, 401);
    }

    /**
     * Forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return static::error($message, null, 403);
    }

    /**
     * Not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return static::error($message, null, 404);
    }

    /**
     * Server error response
     */
    public static function serverError(string $message = 'Server error', ?\Throwable $exception = null): JsonResponse
    {
        $errors = null;
        if (config('app.debug') && $exception) {
            $errors = [
                'error_class' => class_basename($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        return static::error($message, $errors, 500);
    }

    /**
     * Pagination response
     */
    public static function paginated($items, $total, $per_page, $current_page, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $items,
            'pagination' => [
                'total' => $total,
                'per_page' => $per_page,
                'current_page' => $current_page,
                'last_page' => (int)ceil($total / $per_page),
            ],
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'request_id' => request()->header('X-Request-ID') ?? uniqid('req_'),
            ],
        ], 200);
    }
}
