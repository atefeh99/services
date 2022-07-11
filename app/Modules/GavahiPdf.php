<?php

namespace App\Modules;

use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GavahiPdf
{
    public static function getLinkAndBarcode($postalcodes, $user_id, $tracking_code, $values = null, $input = null,
                                             $invalid_values = null)
    {
        $params['postalcode'] = $postalcodes;
        $params['tracking_code'] = $tracking_code;
        $error_msg_part1 = trans('messages.custom.error.msg_part1');

        $client = new Client();
        try {
            $resp = $client->request(
                'POST',
                env("GAVAHIPDF_HOST") . env("GAVAHIPDF_URI"),
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
        return json_decode($resp->getBody()->getContents(), true);
    }

    public static function AddressByCertificateNo($data, $user_id, $values, $input)
    {
        $Cert_no = $data['CertificateNo'];
        
        $client = new Client();
        try {
            $resp = $client->request(
                'GET',
                env('GAVAHIPDF_HOST'),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => ' application/json',
                        'x-user-id' => $user_id
                    ],
                    RequestOptions::QUERY => ['geo' => 0, '$filter' => "barcode eq '$Cert_no'"]
                ]
            );

        } catch (\Exception $e) {
            if ($e->getCode() == '410') {
                $msg = trans('messages.custom.error.1102');
                throw new ServicesException($values, $input, [], 1102, $msg);
            } elseif ($e->getCode() == '404') {
                $msg = trans('messages.custom.error.1101');
                throw new ServicesException($values, $input, [], 1101, $msg);
            } else {
                $msg = trans('messages.custom.error.msg_part1');
                throw new ServicesException($values, $input, [], 9070, $msg);
            }

        }
        if ($resp->getStatusCode() >= 299 || $resp->getStatusCode() < 200) {
            $msg = trans('messages.custom.error.msg_part1');
            throw new ServicesException($values, $input, [], 9070, $msg);
        }

        return json_decode($resp->getBody()->getContents(), true);

    }

}
