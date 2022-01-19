<?php

namespace App\Modules;

use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GavahiPdf
{
    public static function getLinkAndBarcode($postalcodes,$user_id,$tracking_code, $values = null, $input = null,
                                             $invalid_values = null)
    {
        $params['postalcode'] = $postalcodes;
        $params['tracking_code']= $tracking_code;
        $error_msg_part1 = trans('messages.custom.error.msg_part1');

        $client = new Client();
        try {
            $resp = $client->request(
                'POST',
                env("GAVAHIPDF_URL"),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
                        'x-user-id' => $user_id
                    ],
                    RequestOptions::JSON => $params,
                    RequestOptions::QUERY => ['geo' => 0]
                ]
            );
        } catch (\Exception $e) {
            throw new ServicesException($values, $input, $invalid_values, 9070, $error_msg_part1);

        }
        if ($resp->getStatusCode() >= 299 || $resp->getStatusCode() < 200) {
            throw new ServicesException($values, $input, $invalid_values, 9070, $error_msg_part1);
        }
        return json_decode($resp->getBody()->getContents(), true);;


    }

}
