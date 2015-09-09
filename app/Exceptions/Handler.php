<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Middleware\GetUserFromToken;
use App\Exceptions;

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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if($e instanceof NotFoundHttpException)
        {
            return response()->json(['Sorry, the page you are looking for could not be found.'], $e->getStatusCode());
        }

        if($e instanceof MethodNotAllowedException)
        {
            return response()->json(['Sorry, the page you are looking for could not be found.'], $e->getStatusCode());
        }

        if ($e instanceof \Exception){
            $message = [
                'status' => 'error',
                'result' => [
                    'code' => $e->getStatusCode(),
                    'description' => $e->getMessage()
                ]
            ];
            return response($message, $e->getStatusCode());
        }


//        if ($e instanceof APIException){
//            return response()->json([
//                'status' => 'error',
//                'result' => [
//                    'code' => $e->getStatusCode(),
//                    'description' => $e->getMessage()
//                ]
//            ], $e->getStatusCode());
//        }

        return parent::render($request, $e);
    }
}
