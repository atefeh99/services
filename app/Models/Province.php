<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Province extends Model
{
    protected $table = 'province';
    protected $connection = 'gnaf';

//    public static function getPhoneCode()
//    {
//         $value = Cache::rememberForever('areacode',  function()
//        {
//            return  self::get(['phone_code','id as province_id'])->toArray();
//        });
//       // $value = Cache::get('areacode')->toArray();
//        dd($value);
//
//    }

}
