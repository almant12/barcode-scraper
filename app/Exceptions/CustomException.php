<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CustomException extends Exception
{

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof NotFoundHttpException) {

            return response()->json([
                'message' => $exception->getMessage() ?: 'Resource not found'
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'Unauthenticated'
            ], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'This action is unauthorized.'
            ], 403);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'Method Not Allowed'
            ], 405);
        }


        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());
        }

        return response()->json([
            'message' => 'Server message'
        ], 500);
    }
}
