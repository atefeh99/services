<?php

namespace App\Modules;

use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GavahiPdf
{
    public static function getLinkAndBarcode($postalcodes, $values, $input, $invalid_values)
    {
        $params['postalcode'] = $postalcodes;
        $client = new Client();
        try {
            $resp = $client->request(
                'POST',
                env("GAVAHIPDF_HOST") . env("GAVAHIPDF_URI"),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
                        'x-user-id' => '1234'
                    ],
                    RequestOptions::JSON => $params,
                    RequestOptions::QUERY => ['geo' => 0]
                ]
            );
        } catch (\Exception $e) {
            $error_msg_part1 = trans('messages.custom.error.msg_part1');
            throw new ServicesException($values, $input, $invalid_values, 9070,$error_msg_part1);
        }
        if ($resp->getStatusCode() != 200) {
            return null;
        }
        $body = json_decode($resp->getBody()->getContents(), true);
        return $body;


    }

}
