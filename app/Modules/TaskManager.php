<?php


namespace App\Modules;


use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class TaskManager
{

    public static function createPostCodeTask($data, $values, $input)
    {
        $client = new Client();
        try {
            $resp = $client->request(
                'POST',
                env("TASKMANAGER_URL"),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
                        'x-api-key' => env('GNAF_API_KEY'),
                        'token' => env('GNAF_TOKEN'),

                    ],
                    RequestOptions::JSON => $data,
                    RequestOptions::QUERY => ['' => '']
                ]
            );
        } catch (\Exception $e) {
            $error_msg_part1 = trans('messages.custom.error.msg_part1');
            throw new ServicesException($values, $input, [], 9070,$error_msg_part1);
        }
        return json_decode($resp->getBody()->getContents(), true);
    }

    public static function getTask($tracking_number,$values,$input)
    {
        $client = new Client();
        try {
            $resp = $client->request(
                'GET',
                env("TASKMANAGER_URL"),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
//                        'x-api-key' => env('GNAF_API_KEY'),
//                        'token' => env('GNAF_TOKEN'),

                    ],
//                    RequestOptions::JSON => $data,
                    RequestOptions::QUERY => ['$top' => 20,
                        '$skip' => 0,
                        '$filter' => "tracking_number eq $tracking_number"]
                ]
            );
        } catch (\Exception $e) {
            dd($e->getMessage());

            $error_msg_part1 = trans('messages.custom.error.msg_part1');
            throw new ServicesException($values, $input, [], 9070,$error_msg_part1);
        }

        return json_decode($resp->getBody()->getContents(), true);


    }

}
