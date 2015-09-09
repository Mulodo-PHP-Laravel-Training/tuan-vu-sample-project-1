<?php

namespace App\Exceptions;

use App\Exceptions;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $message = null;

        if ($e instanceof ModelNotFoundException)
        {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof MethodNotAllowedHttpException)
        {
            $message = "Method not allowed for this router";
        }


        if ($e instanceof \Exception)
        {
            if (!$message)
            {
                $message = $e->getMessage();
            }
            $response = [
                'status' => 'error',
                'result' => [
                    'code'        => $e->getStatusCode(),
                    'description' => $message
                ]
            ];

            return response()->json($response, $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}
