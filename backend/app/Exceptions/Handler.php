<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Report to external logging service (Sentry, etc.)
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });

        // Handle API validation errors
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Handle API not found
        $this->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found',
                ], 404);
            }
        });

        // Handle API auth errors
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated',
                ], 401);
            }
        });

        // Handle generic API errors
        $this->renderable(function (\Exception $e, $request) {
            if ($request->expectsJson()) {
                $status = 500;
                $message = 'Internal server error';

                if ($e instanceof HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                    $message = $e->getMessage();
                }

                // Don't expose internal errors in production
                if (!app()->isProduction()) {
                    $message = $e->getMessage();
                }

                return response()->json([
                    'message' => $message,
                    'status' => $status,
                ], $status);
            }
        });
    }
}
