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
        $response = [
            'status' => 'error',
            'result' => [
                'code'        => '',
                'description' => ''
            ]
        ];

        if ($e instanceof ModelNotFoundException)
        {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof MethodNotAllowedHttpException)
        {
            $response['result']['code']        = $e->getStatusCode();
            $response['result']['description'] = "Method not allowed for this router";

            return response()->json($response, $e->getStatusCode());
        }


        if ($e instanceof ApiException)
        {
            $response['result']['code']        = $e->getStatusCode();
            $response['result']['description'] = $e->getMessage();

            return response()->json($response, $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}
