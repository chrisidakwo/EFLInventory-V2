<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception) {
        parent::report($exception);

        //	    if (auth()->id()) {
//		    $stack_trace = $exception->getTraceAsString();
//		    $error_message = $exception->getMessage();
//		    ErrorLog::AddNewError($exception, $error_message, $stack_trace);
//	    }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception|Throwable|HttpExceptionInterface $exception
     * @throws Exception
     */
    public function render($request, $exception) {
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        }

        return parent::render($request, $exception);
    }

    protected function renderHttpException(HttpExceptionInterface $e) {
        if (view()->exists('errors.' . $e->getStatusCode())) {
            return response()->view('errors.' . $e->getStatusCode(), [], $e->getStatusCode());
        }

        return response()->view('errors.404', [], $e->getStatusCode());
    }
}
