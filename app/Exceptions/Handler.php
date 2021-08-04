<?php

namespace App\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
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
    public function render($request, Throwable $exception)
    {

        $response = parent::render($request, $exception);
        if (env('APP_DEBUG')) {
            $return_object = [
                'data' => [],
                'status' => 200
            ];
            if ($exception instanceof RequestRulesException) {

                $return_object = [
                    'data' => [
                        'ErrorMessage' => trans('messages.custom.'.Response::HTTP_NOT_FOUND),
                        'ErrorCode' => $exception->getErrorCode(),
                        'TraceID'=>''
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];

            }
            return response()
                ->json($return_object['data'], $return_object['status'])
                ->header('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }



}
