<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedUserException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;
use App\Http\Controllers\Process\SynchronizationController;
use App\Http\Controllers\Task\TaskManagementController;
use App\Http\Controllers\Task\CommentController;
use Illuminate\Validation\Rule;

trait RulesTrait
{


    public static function rules()
    {
        return [
            RouteCRUDController::class => [
                'createItem' => [
                    'uri' => 'required|string',
                    'description' => 'required|string'
                ],
                'readItem' => [
                    'id' => 'numeric|required',
                ],
                'updateField' => [
                    'id'=>'required|numeric',
                    'uri' => 'required|string',
                    'description' => 'required|string'
                ],
                'deleteRecord' => [
                    'id' => 'integer',
                ],
                'showAll' => [

                ]
            ],
        ];
    }

    public static function checkRules($data, $function, $code)
    {
        $controller = __CLASS__;
        if (is_object($data)) {
             $validation = Validator::make(
                $data->all(),
                self::rules()[$controller][$function]
            );
        } else {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function]
                );
            }

        if ($validation->fails()) {
            throw new RequestRulesException($validation->errors()->getMessages(), $code);
        }


        return $validation->validated();
    }
}
