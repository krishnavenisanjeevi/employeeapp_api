<?php

namespace App\Exceptions;

use App\Models\Result;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
            // ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //return parent::render($request, $exception);
        $rendered = parent::render($request, $exception);
        $statusCode = Response::HTTP_OK;
        if ($exception instanceof ModelNotFoundException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            $message = "Record not found";
        } else if ($exception instanceof QueryException) {
            if ($exception->errorInfo[1] == 1062) {
                $message = "Duplicate data, please check";
            } else if ($exception->errorInfo[1] == 1216) {
                $message = "Foreign key constraint violation";
            } else if ($exception->errorInfo[1] == 1048) {
                $message = "Column cannot be null";
            } else if ($exception->errorInfo[1] == 1364) {
                $message = "Field doesn't have a default value";
            } else if ($exception->errorInfo[1] == 1451) {
                $message = "Cannot delete or update a parent row: a foreign key constraint fails";
            } else if($exception->getCode() == 1044 || $exception->getCode() == 1045){
                $message = "Database access denied, please contact admin.";
            } else if($exception->getCode() == 2002){
                $message = "Database server down, please contact admin.";
            } else {
                $message = $exception->getMessage();
            }
        } else if ($exception instanceof AuthorizationException) {
            $statusCode = Response::HTTP_UNAUTHORIZED;
            $message = Response::$statusTexts[$statusCode];
        } else if ($exception instanceof NotFoundHttpException) {
            $statusCode = Response::HTTP_NOT_FOUND;
            $message = $exception->getMessage() ? $exception->getMessage() : Response::$statusTexts[$rendered->getStatusCode()];
        } elseif ($exception instanceof HttpException) {
            $message = $exception->getMessage() ? $exception->getMessage() : Response::$statusTexts[$rendered->getStatusCode()];
            $statusCode = $rendered->getStatusCode();
        } elseif ($exception instanceof QueryException) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = "Internal server error";
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = env('APP_DEBUG', false) ? $exception->getMessage() : Response::$statusTexts[$statusCode];
        }

        // Resonse 
        return response()->json(Result::failed($message), $statusCode);
    }
}