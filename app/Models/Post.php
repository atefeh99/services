<?php

namespace App\Models;

use App\Exceptions\RequestRulesException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Post extends Model
{
    // use Common;
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
        'building_name'
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
    protected static $_table = 'post_data_integrated';
    protected $connection = 'gnaf';

//    protected $table = 'post_data_integrated_01_qom_100_01_new';

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

    public static function searchinarray($input, $value, $out_fields)
    {
        $a = Post::whereRaw("$input @> array[?]", [$value])->first();
        if (!$a) {   throw new RequestRulesException($input, "404");}

        if ($out_fields[0] == "ST_X(geom),ST_Y(geom)") {

            $result = Post::whereRaw("$input @> array[?]", [$value])->get(array(DB::raw($out_fields[0])));
            $result = $result->toArray();
            return $result[0];
        } else {
            $result = Post::whereRaw("$input @> array[?]", [$value])->get($out_fields);
            $result = $result->toArray();
            return $result[0];
        }

    }

    public static function search($input, $value, $out_fields)
    {
        $a = Post::where($input, $value)->first();
        if (!$a) {    throw new RequestRulesException($input, "404");}

        //dd($out_fields);
        if ($out_fields[0] == "ST_X(geom),ST_Y(geom)") {

            $result = Post::where($input, $value)->get(array(DB::raw($out_fields[0])));
            $result = $result->toArray();
            return $result[0];
        } else {
            $result = Post::where($input, $value)
                ->get($out_fields)->unique(function ($item) use ($out_fields) {
                    $temp = "";
                    foreach ($out_fields as $out_field) {
                        $temp .= $item[$out_field];
                    }
                    return $temp;
                                                                               })->toArray();

            return $result[0];
        }

    }


}
