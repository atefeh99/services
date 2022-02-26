<?php


namespace App\Modules;


use App\Exceptions\AuthenticationException;
use App\Exceptions\ServicesException;
use GuzzleHttp\{Client,
    RequestOptions,
};
use GuzzleHttp\Exception\{
    GuzzleException,
    ClientException
};
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AppRegistration
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();

    }

    public function createRequest($method, $uri, $options)
    {
        $code = null;
        $body = null;
        try {
            $response = $this->client->request(
                $method,
                env('APPREG_HOST') . $uri,
                $options
            );
            Log::info(__FUNCTION__.':response_body:'.json_decode($response->getBody()->getContents(), true));

            $code = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $code = $e->getCode();
            $body = json_decode($e->getResponse()->getBody()->getContents(), true);
            Log::info(__FUNCTION__.':exception_body:'.json_decode($e->getResponse()->getBody()->getContents(), true));

        }
        return ['code' => $code, 'body' => $body];


    }

    public function userToken($data)
    {
        $options = [
            RequestOptions::JSON => [
                "grant_type" => $data['grant_type'],
                "client_id" => env('APPREG_CLIENT_ID'),
                "client_secret" => env('APPREG_CLIENT_SECRET'),
                "username" => $data['username'],
                "password" => $data['password'],
            ],

        ];
        $resp = $this->createRequest('POST', env('APPREG_USER_TOKEN_URI'), $options);
        if ($resp['code'] == 200) {
            return $resp['body']['access_token'];
        } else {
            Log::info('get user token failed: '.$resp['body']);
            if (array_key_exists('message', $resp['body'])) {
                if (($resp['code'] == 404 && $resp['body']['message'] == "Resource Not Found")
                    || ($resp['code'] == 401 && $resp['body']['message'] == "your password is not correct!")
                ){
                    throw new AuthenticationException(417, trans('messages.custom.error.services_auth_417'));
                }
            }
                //todo else:handle captcha
        }
        throw new AuthenticationException(401, trans('messages.custom.error.services_auth_401'));

    }

    public function myself($access_token)
    {
        $options = [
            RequestOptions::HEADERS => [
                'x-client-id' => env('X_CLIENT_ID'),
                'x-scopes' => [
                    'basic',
                    'admin'
                ]

            ],

        ];
        if (App::environment('local')) {
            $options[RequestOptions::HEADERS] = [
                'x-api-key' => env('APPREG_API_KEY'),
                'x-user-id' => env('ADMIN_USER_ID'),
                'token' => $access_token
            ];
        }
        $resp = $this->createRequest('GET', env('APPREG_MYSELF_URI'), $options);

        if ($resp['code'] == 200) return [
            'api_key'=>$resp['body']['my_app']['access_token']['token'],
            'expires_in'=> $resp['body']['my_app']['access_token']['expired_at']];
         else {
             Log::info('myself response: '. $resp['body']);
             throw new AuthenticationException(
                 401,
                 trans('messages.custom.error.services_auth_401')
             );
         }
    }

}
