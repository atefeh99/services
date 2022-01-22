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
        return json_decode($resp->getBody()->getContents(), true);;


    }

    public static function AddressByCertificateNo($data, $user_id)
    {
//        {{Host}}?geo=0&$filter= barcode eq '21212817790534906111'
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
//                    RequestOptions::JSON => $params,
                    RequestOptions::QUERY => ['geo' => 0, '$filter' => "barcode eq '$Cert_no'"]
                ]
            );

        } catch (\Exception $e) {
            if ($e->getCode() == '410') {
                $msg = trans('messages.custom.error.1102');
                throw new ServicesException(null, null, [], null, null, null, 1102, $msg, 'empty');
            } elseif ($e->getCode() == '404') {
                $msg = trans('messages.custom.error.1103');
                throw new ServicesException(null, null, [], null, null, null, 1103, $msg, 'empty');
            } else{
                $msg = trans('messages.custom.error.msg_part1');
                throw new ServicesException(null, null, [], null, null, null,9070 , $msg, 'empty');
            }

        }

        if ($resp->getStatusCode() >= 299 || $resp->getStatusCode() < 200) {
        throw new ServicesException(null, null, [], null, null, null,9070 , $msg, 'empty');
        }

        return json_decode($resp->getBody()->getContents(), true);


    }


}
