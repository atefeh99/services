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
            $body = ['post_unit' => $post_unit];
            $resp = $this->request(
                'POST',
                env("PAYMENT_URL") . env("INVOICE_URI"),
                null,
                $body,
                null
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }

        Log::info('invoice created');
        $body = json_decode($resp->getBody()->getContents(), true);
        return $body["data"]["id"];
    }

    public function insertInvoiceLine($invoice_id, $quantity, $service_id)
    {
        try {
            $body = [
                "quantity" => $quantity,
                "service_id" => $service_id
            ];
            $resp = $this->request(
                'POST',
                env("PAYMENT_URL") . env("INVOICE_URI") . "/$invoice_id/rows",
                null,
                $body,
                null
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }
        Log::info('invoice line inserted');
        $body = json_decode($resp->getBody()->getContents(), true);
        return $body["data"]["invoice_lines"][0]["id"];
    }

    public function getServices()
    {
        try {
            $resp = $this->request(
                'GET',
                env("PAYMENT_URL") . env("SERVICES_URI")
            );

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }
        $body = json_decode($resp->getBody()->getContents(), true);
        if (array_key_exists('odata.count', $body) && $body['odata.count'] > 0) {
            foreach ($body['value'] as $service) {
                if ($service['name'] != 'درخواست کد پستی') {
                    continue;
                } else {
                    Log::info('get payment service`s id');
                    return $service['id'];
                }
            }
        }
    }

    public function initPayment($invoice_id)
    {
        try {
            $resp = $this->request(
                'GET',
                env("PAYMENT_URL") . env("INVOICE_URI") . "/$invoice_id/pay"
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }
        Log::info('init payment table success');
        $body = json_decode($resp->getBody()->getContents(), true);

        return $body['data']['success'];
    }

    public function getByUserId($invoice_line_id)
    {

        $tracking_code = null;
        try {
            $resp = $this->request(
                'GET',
                env("PAYMENT_URL") . env("PAYMENTS_URI")
            );

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
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }

        $body = json_decode($resp->getBody()->getContents(), true);
        if ($body['odata.count'] > 0) {
            foreach ($body['value'] as $payment) {
                foreach ($payment['invoice']['invoice_lines'] as $line) {
                    if ($line['id'] != $invoice_line_id) {
                        continue;
                    } else {
                        Log::info('tracking_code got successfully');
                        $tracking_code = $payment['tracking_code'];

                    }
                }
            }
        }
        return $tracking_code;
    }

}
