<?php

namespace App\Exceptions;

use App\Traits\RestExceptionHandlerTrait;
use App\Traits\RestTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RestTrait;
    use RestExceptionHandlerTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if (!$this->isApiCall($request)) {
            if ($exception instanceof MethodNotAllowedException) {
                return abort(404);
            }
            if ($exception instanceof \InvalidArgumentException) {
                return abort(404);
            }
            return parent::render($request, $exception);
        }

        if ($exception instanceof PostTooLargeException) {
            return response([
                'errors'=> [
                    'files'=> ['File too large!']
                ],
                'message' => 'File too large!'
            ], 422);
        }


        return parent::render($request, $exception);
        //  return $this->getJsonResponseForException($request, $exception);

    }
}
