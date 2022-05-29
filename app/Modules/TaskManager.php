<?php


namespace App\Modules;


use App\Exceptions\ServicesException;
use App\Helpers\GuzzleRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class TaskManager
{

    public $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function request($method, $url, $headers = null, $body = null, $query = null)
    {
        return $this->client->request(
            $method,
            $url,
            [
                RequestOptions::HEADERS => $headers,
                RequestOptions::JSON => $body,
                RequestOptions::QUERY => $query
            ]
        );

    }


    public function createPostCodeTask($data, $values, $input, $user_id)
    {
        try {
            $headers = [
                'Content-Type' => ' application/json',
                'x-user-id' => $user_id,
                'x-api-key' => env('GNAF_API_KEY'),
                'token' => env('GNAF_TOKEN')
            ];
            $resp = $this->client->request(
                'POST',
                env("TASKMANAGER_URL"),
                [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::JSON => $data,
                ]
            );
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null,
                null, null, null, null, null, -2, trans('messages.error.-2'), 'empty');
        } catch (\Exception $e) {
//            dd($e->getMessage());
            if (!empty($values) && !empty($input)) {
                $error_msg_part1 = trans('messages.custom.error.msg_part1');
                throw new ServicesException($values, $input, [], 9070, $error_msg_part1);
            } else {
                throw new ServicesException(null, null, null,
                    null, null, null, -2, trans('messages.error.-2'), 'empty');
            }
        }
        dd(json_decode($resp->getBody()->getContents(), true));
        return json_decode($resp->getBody()->getContents(), true);
    }

    public function getTask($tracking_number, $values, $input, $user_id)
    {
        try {
            $headers = [
                'Content-Type' => ' application/json',
                'x-user-id' => $user_id,
//                        'x-api-key' => env('GNAF_API_KEY'),
//                        'token' => env('GNAF_TOKEN'),
            ];
            $query = [
                '$top' => 20,
                '$skip' => 0,
                '$filter' => "tracking_number eq $tracking_number"
            ];

            $resp = $this->client->request(
                'GET',
                env("TASKMANAGER_URL"),
                [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::QUERY => $query
                ]

            );
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null,
                null, null, null, null, null, -2, trans('messages.error.-2'), 'empty');
        } catch (\Exception $e) {
            $error_msg_part1 = trans('messages.custom.error.msg_part1');
            throw new ServicesException($values, $input, [], 9070, $error_msg_part1);
        }
        return json_decode($resp->getBody()->getContents(), true);
    }
}
