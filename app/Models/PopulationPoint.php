<?php


namespace App\Models;


use App\Exceptions\ServicesException;
use Illuminate\Database\Eloquent\Model;

class PopulationPoint extends Model
{
    protected $table = 'population_point';

    protected $connection = 'gnaf';

    public static function getActionAreas($population_point_id)
    {
        $fields = [
            'id',
            'name',
            'province_id',
            'county_id',
            'zone_id',
            'rural_id'
        ];
        $item = self::with('province','county','zone','rural')->where('id',$population_point_id)->get($fields)->toArray();
        if (count($item) > 0) {
            return $item;
        }
        $res_message = trans('messages.custom.error.-8');
        throw new ServicesException(null,
            null,
            [],
            null,
            null,
            null,
            -8,
            $res_message,
            'empty');

    }
    public function province()
    {
        return $this->hasOne(Province::class,'id','province_id');
    }
    public function county()
    {
        return $this->hasOne(County::class,'id','county_id');
    }
    public function zone()
    {
        return $this->hasOne(Zone::class,'id','zone_id');
    }
    public function rural()
    {
        return $this->hasOne(Rural::class,'id','rural_id');
    }
}
