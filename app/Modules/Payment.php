<?php


namespace App\Modules;


use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class Payment
{
    public static function getTrackingCode($transaction_id, $values = null, $input = null, $invalid_values = null)
    {
        $error_msg_part1 = trans('messages.custom.error.msg_part1');
        $client = new Client();
        try {
            $resp = $client->request(
                'GET',
                env("PAYMENT_URL"),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
                        'x-scopes' => 'admin',
                    ],
                    RequestOptions::QUERY => ['$filter' => 'payment_ref_num eq ' . $transaction_id]
                ]
            );
        } catch (\Exception $e) {
            if($e->getCode() === 404){
                $res_msg = trans('messages.custom.error.transaction_not_found');
                throw new ServicesException(null,
                    null,
                    [],
                    null,
                    null,
                    null,
                    -34,
                    $res_msg,
                    'empty'
                );
            }
            throw new ServicesException($values, $input, [], 9070, $error_msg_part1);
        }

        $body = json_decode($resp->getBody()->getContents(), true);

        if (array_key_exists('tracking_code', $body['value'][0]) && !empty($body['value'][0]['tracking_code'])) {
            return $body['value'][0]['tracking_code'];
        } else {
            //??
            Log::error('tracking code not set or null');
        }
    }

}
