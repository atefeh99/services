<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Province extends Model
{
    protected $table = 'province';
    protected $connection = 'gnaf';

    protected $hidden =[
        "id",
      "country_id",
      "en_name",
      "code",
      "geocoded_point" ,
      "geometry",
        "grid_code" ,
      "phone_code" ,
      "opt_approval" ,
      "attachment",
      "created_at",
      "updated_at",
      "deleted_at"
    ];
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
