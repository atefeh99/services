<?php

namespace App\Models;

use App\Exceptions\RequestRulesException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


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

//
    protected $table = 'sina_units';
    protected static $_table = 'sina_units';

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
        $result = self::query();
            if ($out_fields[0] == "ST_X(geom),ST_Y(geom)" /*|| $out_fields[0] == "ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))"*/) {
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
//            $result = Post::whereRaw("$input @> array[?]", [$value]);
                if (!empty($scopes)) {
                    $result = $result->actionArea($scopes);
                }
                $result = $result->get(DB::raw($temp))
                    ->toArray();
//            dd($result);


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
//                dd($result->toSql());
            $result = $result->get($out_fields)
                ->toArray();
//                dd($result);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
        $f = [];
//        dd($result);
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
//            dd($f);
            return $f;
        } else {
            return null;
        }

    }

    public static function search($input, $values, $out_fields, $scopes)
    {
        if ($out_fields[0] == "ST_X(geom),ST_Y(geom)" || $out_fields[0] == "ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))") {
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
                dd($e->getMessage());

            }
        }
        if (count($result) > 0) {
            return $result;
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
        return $value->phone_code;
    }


}
