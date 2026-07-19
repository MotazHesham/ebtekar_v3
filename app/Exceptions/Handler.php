<?php

namespace App\Exceptions;

use App\Http\ResponseHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\UserFriendlyException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        // Handle UserFriendlyException
        $this->renderable(function (UserFriendlyException $e, $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::returnResponse(
                    $e->getMessage(),
                    $e->data,
                    false,
                    '001'
                );
            }
        });

        // Handle unauthenticated exceptions
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::returnResponse(
                    'قم بتسجيل الدخول أولا',
                    ['redirect' => 'login'],
                    false,
                    '002'
                );
            }
        });

        // Handle unauthorized exceptions 
        $this->renderable(function (UnauthorizedException $e, $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::returnResponse(
                    'لا تملك الصلاحية الكافية',
                    null,
                    false,
                    '003'
                );
            }
        });

        // Handle not found exceptions for API routes
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return ResponseHelper::returnResponse(
                    env('APP_ENV') !== 'local' ? 'الصفحة غير موجودة' : $e->getMessage(),
                    null,
                    false,
                    '004'
                );
            }
        });

        // Handle unprocessable content (validation errors)
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                $errorsCount = count($e->validator->errors());
                return ResponseHelper::returnResponse(
                    $e->validator->errors()->first() . ($errorsCount > 1 ? trans('validation.more_errors', ['count' => $errorsCount]) : ''),
                    $e->validator->errors(),
                    false,
                    '005'
                );
            }
        });

        // Handle general exceptions for API routes
        $this->renderable(function (\Exception $e, $request) {

            $message = !app()->isProduction()
                ? $e->getMessage()
                : trans('api.errors.somethingWentWrong');

            if ($request->is('api/*')) {

                Log::error('API Error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);

                return ResponseHelper::returnResponse(
                    $message,
                    null,
                    false,
                    '006'
                );
            }
        });
    }
}
