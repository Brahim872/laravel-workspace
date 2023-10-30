<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Intervention\Image\Exception\NotFoundException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function render($request, Throwable $exception)
    {


        if ($exception instanceof ResourceNotFoundException) {
            return returnResponseJson([
                'message' => 'NOT FOUND.'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return returnResponseJson([
                'message' => 'API rate limit exceeded. Please try again later.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }
        if ($exception instanceof UnauthorizedException) {
            return returnResponseJson([
                'message' => 'User is not logged in.'
            ], Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return returnResponseJson([
                'message' => 'method not allowed.'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }
        return parent::render($request, $exception);
    }


    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
