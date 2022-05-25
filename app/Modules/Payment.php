<?php


namespace App\Modules;


use App\Exceptions\ServicesException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class Payment
{
    public $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function request($method, $url, $headers = null, $body = null, $query = null)
    {
        $resp = null;
        try {
            $resp = $this->client->request(
                $method,
                $url,
                [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::JSON => $body,
                    RequestOptions::QUERY => $query
                ]
            );
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
        }
        return $resp;
    }

    public function getTrackingCode($transaction_id, $values = null, $input = null, $invalid_values = null)
    {
        $error_msg_part1 = trans('messages.custom.error.msg_part1');
        $headers = [
            'Content-Type' => ' application/json',
            'x-scopes' => 'admin',
        ];
        $query = ['$filter' => 'payment_ref_num eq ' . $transaction_id];
        try {
            $resp = $this->request(
                'GET',
                env("PAYMENT_URL") . env("PAYMENTS_URI"),
                $headers,
                null,
                $query);

        } catch (\Exception $e) {
            if ($e->getCode() === 404) {
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
            Log::error('tracking code not set or null');
        }
    }

    public function createInvoice($post_unit)
    {
        try {
            $headers = [
                'x-user-id' => '1544a2ce-9634-4ae3-83ff-02becd4e6450',
                'Content-Type' => ' application/json',
                'x-scopes' => 'admin',
            ];
            $body = ['post_unit' => $post_unit];
            $resp = $this->request(
                'POST',
                env("PAYMENT_URL") . env("INVOICE_URI"),
                $headers,
                $body,
                null
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }

        $body = json_decode($resp->getBody()->getContents(), true);

        return $body["data"]["id"];
    }

    public function insertInvoiceLine()
    {
        try {
            $headers = [
                'x-user-id' => '1544a2ce-9634-4ae3-83ff-02becd4e6450',
                'Content-Type' => ' application/json',
                'x-scopes' => 'admin',
            ];
            $resp = $this->request(
                'POST',
                env("PAYMENT_URL") . env("INVOICE_URI"),
                $headers,
                $body,
                null
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }

        $body = json_decode($resp->getBody()->getContents(), true);

        return $body["data"]["id"];
    }

    public function getServices()
    {
        try {
            $headers = [
                    'x-user-id' => '1544a2ce-9634-4ae3-83ff-02becd4e6450',
                    'Content-Type' => ' application/json',
                    'x-scopes' => 'admin',
                ];
            $resp = $this->request(
                'POST',
                env("PAYMENT_URL") . env("INVOICE_URI"),
                $headers
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }

    }

}
