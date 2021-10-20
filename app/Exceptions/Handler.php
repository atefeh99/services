<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
//    protected $dontFlash = [
//        'current_password',
//        'password',
//        'password_confirmation',
//    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function report(Throwable $exception)
    {
        $debug = env('APP_DEBUG');

        if ($debug) {
            return parent::report($exception);
        }
    }


    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        $debug = env('APP_DEBUG');
        if (!$debug) {
            $return_object = [
                'data' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => trans('messages.custom.' . Response::HTTP_INTERNAL_SERVER_ERROR),
                    'code' => 105
                ],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
//dd(GetCallingMethodName($e));
            if ($e instanceof ServicesException) {
                $return_object = [
                    'ResCode' => $e->getResCode(),
                    'ResMsg' => $e->getResMessage(),
                    'Data' => $e->getData()

                ];
                return response()
                    ->json($return_object)
                    ->header('Access-Control-Allow-Origin', '*');
            } elseif ($e instanceof RequestRulesException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => $e->getMessage(),
                        'fields' => $e->getFields(),
                        'code' => 104
                    ],
                    'status' => Response::HTTP_BAD_REQUEST
                ];
            } elseif ($e instanceof ModelNotFoundException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => trans('messages.custom.' . Response::HTTP_NOT_FOUND),
                        'code' => 105
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
