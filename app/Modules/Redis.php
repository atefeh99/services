<?php


namespace App\Modules;

use Predis\Client;


class Redis
{
    private static $redis;

    private static function createRedisClient()
    {
        self::$redis = new Client(
            [
                'scheme' => 'tcp',
                'host' => env('REDIS_HOST', '127.0.0.1'),
                'port' => env('REDIS_PORT', 6379)
            ]
        );
        return self::$redis;
    }
}
