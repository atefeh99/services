<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Post extends Model
{

    protected $fillable = [
        'id',
        'gnaf',
        'avenue',
        'geom',
        'preaven_id',
        'parish',
        'bld_name',
        'bld_des',
        'statename',
        'townname',
        'zonename',
        'villagename',
        'locname',
        'tourno',
        'locationtype',
        'locationname',
        'parish',
        'avenuetypename',
        'avenue',
        'pelak',
        'floorno',
        'building_name',
        'parcel',
        'province_id',
    ];

    protected $postgisFields = [
        'geom',
        'parcel'
    ];
    protected $postgisTypes = [
        'geom' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ],
        'parcel' => [
            'geomtype' => 'geometry',
            'srid' => 4326
        ]
    ];

    protected $table = 'sina_units_table';
    protected static $_table = 'sina_units_table';

    protected $connection = 'gnaf';


    public static $role = '';

    public static $defaultFields = [
        'id',
        'partrowno'
    ];

    public static $uniqueFields = [
        'statename',
        'townname',
        'zonename',
        'villagename',
        'locationname',
        'locationtype',
        'parish',
        'tour',
        'mainavenue',
        'preaventypename',
        'preaven',
        'avenuetypename',
        'avenue',
        'pelak'
    ];

    public static $addressFields = [
        'statename',
        'locationname',
        'locationtype',
        'parish',
        'tour',
        'mainavenue',
        'preaventypename',
        'preaven',
        'avenuetypename',
        'avenue',
        'pelak',
        'floorno',
        'unit',
        'postalcode'
    ];
    protected $appends = [
        'areacode'
    ];
    protected $hidden = [
        'province_id'
    ];

    public static function searchInArray($input, $value, $out_fields, $scopes)
    {
//toDo if statement should be correct
        if(empty($value)){
            return null;
        }
        $result = self::query();
        if (in_array("ST_X(geom),ST_Y(geom)", $out_fields) || in_array("ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))", $out_fields)) {
            $i = 0;
            $count = count($out_fields);
            $temp = "";
            foreach ($out_fields as $field) {
                $temp .= $field;
                if (++$i !== $count) {
                    $temp .= ',';
                }
            }
            $result = $result->where(function ($query) use ($value) {
                foreach ($value as $item) {
                    $query = $query->orWhereRaw("tels @> '[\"$item\"]'");
                }
                return $query;
            });
            if (!empty($scopes)) {
                $result = $result->actionArea($scopes);
            }
            $result = $result->get(DB::raw($temp))
                ->toArray();
        } else {
            try {
                $result = $result->where(function ($query) use ($value) {
                    foreach ($value as $item) {
                        $query = $query->orWhereRaw("tels @> '[\"$item\"]'");
                    }
                    return $query;
                });
                if (!empty($scopes)) {
                    $result = $result->actionArea($scopes);
                }
                $result = $result->get($out_fields)
                    ->toArray();
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        $f = [];
        foreach ($value as $item) {
            foreach ($result as $r) {
                foreach ($r['tels'] as $tel) {
                    if ($tel == $item) {
                        $f[$item] = $r;
                    }
                }
            }
        }
        if (count($f) > 0) {
            return $f;
        } else {
            return null;
        }
    }

    public static function search($input, $values, $out_fields, $scopes)
    {
        $result = [];
        if (in_array("ST_X(geom),ST_Y(geom)", $out_fields)
            || in_array("ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))", $out_fields)) {
            $i = 0;
            $count = count($out_fields);
            $temp = "";
            foreach ($out_fields as $field) {
                $temp .= $field;
                if (++$i !== $count) {
                    $temp .= ',';
                }

            }
            $result = self::whereIn($input, $values);
            if (!empty($scopes)) {
                $result = $result->actionArea($scopes);
            }
            $result = $result->get(DB::raw($temp))
                ->keyby($input)
                ->toArray();

        } else {
            try {
                $result = self::whereIn($input, $values);
                if (!empty($scopes)) {
                    $result = $result->actionArea($scopes);
                }
                $result = $result->get($out_fields)
                    ->keyby($input)
                    ->toArray();
            } catch (\Exception $e) {
                Log::error($e->getMessage());

            }
        }

        if (count($result) > 0) {
            return $result;
        } else {
            return null;
        }

    }

    //get center of polygon and find parcels that contains the center
    public static function postcodeByParcel($scopes, $polygon, $out_fields, $geometry)
    {
        $postalcodes = [];
        $query = '';

        try {
            $query = self::whereRaw("st_contains(parcel,st_setsrid(ST_Centroid(ST_GeomFromText(?)),4326))"
                , ["POLYGON (($polygon))"]
            );
            if (!empty($scopes)) {
                $query = $query->actionArea($scopes);
            }
            $query = $query
                ->get($out_fields);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        if (count(collect($query)) > 0) {
            foreach (collect($query) as $item) {
                $postalcodes[] = $item['postalcode'];
            }
            return $postalcodes;
        } else {
            return null;
        }

    }


    public function scopeActionArea($query, $scopes)
    {
        if (array_key_exists('province', $scopes)) {
            $query->whereIn('province_id', $scopes['province']);
        }
        return $query;
    }

    public function getTelsAttribute()
    {
        if ($this->attributes['tels'] == "[null]" || !$this->attributes['tels'])
            return null;

        return json_decode($this->attributes['tels'], true);
    }

    public function getAreacodeAttribute()
    {
        $value = Cache::rememberForever($this->province_id . '_phone_code', function () {
            return Province::where('id', $this->province_id)->first();
        });
        if (!empty($value) && array_key_exists('phone_code', get_object_vars($value)['attributes'])) {
            return $value->phone_code ?? "";
<<<<<<< HEAD

=======
>>>>>>> dc6561e771088ad56da0d97435ec47ba653f6a95
        }
        return "";
    }

    public function getAvenueAttribute()
    {
        return $this->attributes['avenuetypename'] . ' ' . $this->attributes['avenue'];
    }

    public function getPreavenAttribute()
    {
        return $this->attributes['preaventypename'] . ' ' . $this->attributes['preaven'];
    }

    public function getFloornoAttribute()
    {
        if ($this->attributes['floorno'] == 0) {
            return 'همکف';
        }
        return $this->attributes['floorno'];
    }
}
