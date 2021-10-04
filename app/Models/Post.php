<?php

namespace App\Models;

use App\Exceptions\RequestRulesException;
use Illuminate\Database\Eloquent\Model;
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
        'parcel'
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

    public static function searchInArray($input, $value, $out_fields)
    {
//        dd($input, $value, $out_fields);

//toDo if statement should be correct
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

            $result = Post::whereRaw("$input @> array[?]", [$value])
                ->get($temp)
                ->unique(function ($item) use ($out_fields) {
                    $temp = "";
                    foreach ($out_fields as $out_field) {
                        $temp .= $item[$out_field];
                    }
                    return $temp;
                })
                ->keyby($input)
                ->toArray();
        } else {
            $result = Post::whereRaw("$input @> array[?]", [$value])
                ->get($out_fields)
                ->unique(function ($item) use ($out_fields) {
                    $temp = "";
                    foreach ($out_fields as $out_field) {
                        $temp .= $item[$out_field];
                    }
                    return $temp;
                })
                ->keyby($input)
                ->toArray();
        }
        if (count($result) > 0) {
            return $result;
        } else {
            return null;
        }

    }

    public static function search($input, $values, $out_fields)
    {
//        dd($out_fields);


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

            $result = self::whereIn($input, $values)
                ->get(DB::raw($temp))
                ->unique(function ($item) use ($out_fields) {
                    $temp = "";
                    foreach ($out_fields as $out_field) {
                        $temp .= $item[$out_field];
                    }
                    return $temp;
                })
                ->keyby($input)
                ->toArray();
//            dd($result);
        } else {
            $result = self::whereIn($input, $values)
                ->get($out_fields)
                ->unique(function ($item) use ($out_fields) {
                    $temp = "";
                    foreach ($out_fields as $out_field) {
                        $temp .= $item[$out_field];
                    }
                    return $temp;
                })
                ->keyby($input)
                ->toArray();

//            dd($result);
        }
        if (count($result) > 0) {
            return $result;
        } else {
            return null;
        }

    }


}
