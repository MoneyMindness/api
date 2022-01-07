<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Kinda janky but works I guess
        // Replaces the default validator errors with flattened version.
        if (
            $e instanceof ValidationException
            && $this->isFromApp($request->header('User-Agent'))
        ) {
            return $this->handleAppException($e);
        }

        return parent::render($request, $e);
    }

    private function handleAppException(ValidationException $e): JsonResponse
    {
        return response()->json([
            'message' => $e->getMessage(),
            'data' => collect($e->errors())->flatten()
        ], 422);
    }

    private function isFromApp($userAgent): bool
    {
        return explode("/", $userAgent)[0] === "MoneyMindnessClient";
    }


}
