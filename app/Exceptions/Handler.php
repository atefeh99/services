<?php

namespace App\Exceptions;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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

            } elseif ((strpos($request->getRequestUri(), 'Telephones') == true
                    || strpos($request->getRequestUri(), 'Postcode') == true) &&
                ($e instanceof QueryException))
            {
                $input = $request->input;
                $info = $request->input(Constant::INPUTMAPS[$input]);
                $msg_part1 = trans('messages.custom.error.msg_part1');
                $msg_part2 = trans('messages.custom.error.9070');



                throw new ServicesException($info,
                    $input,
                    null,
                    9070,
                    $msg_part1,
                    $msg_part2
                );
            }
            return response()
                ->json($return_object['data'], $return_object['status'])
                ->header('Access-Control-Allow-Origin', '*');
        }
        return $response;
    }
}
