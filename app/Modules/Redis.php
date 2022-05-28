<?php


namespace App\Modules;

use Predis\Client;


class Redis
{
    private static $redis;

    const STATE_ID_PREFIX = 'df:1:province_id';


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

    public static function getPostUnit($building)
    {
        $redis = self::createRedisClient();
        $province_id = $building['province_id'];
        $prefix1 = self::STATE_ID_PREFIX;
        $key = "$prefix1:$province_id:state_id";
        return $redis->get($key);

    }
}
