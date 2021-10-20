<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\Post;
use App\Exceptions\ServicesException;

class Gnafservices
{

    public static $composite_response = [
        'Position' => [
            'st_x' => 'Longitude',
            'st_y' => 'Latitude'
        ]
        ,
        'Telephones' => [
            'tel' => 'SubscriberNumber',
            'areacode' => 'AreaCode'
        ],
        'Postcode' => [
            'postalcode' => 'Value'
        ],
        'Address' => [
            'statename' => 'Province',
            'townname' => 'TownShip',
            'zonename' => 'Zone',
            'villagename' => 'Village',
            'locationtype' => 'LocalityType',
            'locationname' => 'LocalityName',
//            'localitycode'=>'LocalityCode',
            'parish' => 'SubLocality',
            'preaven' => 'Street2',
            'avenue' => 'Street',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'description'=>'Description',

        ],
        'AddressAndTelephones' => [
            'statename' => 'Province',
            'townname' => 'TownShip',
            'zonename' => 'Zone',
            'villagename' => 'Village',
            'locationtype' => 'LocalityType',
            'locationname' => 'LocalityName',
//            'localitycode'=>'LocalityCode',
            'parish' => 'SubLocality',
            'avenue' => 'Street',
            'preaven' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'description'=>'Description',
//        'areacode'=>'PrePhone',
//            'tel' => 'TelephoneNo'

        ],
        'AddressAndPostcode' => [
            'postalcode' => 'Postcode',
            'statename' => 'Province',
            'townname' => 'TownShip',
            'zonename' => 'Zone',
            'villagename' => 'Village',
            'locationtype' => 'LocalityType',
            'locationname' => 'LocalityName',
//            'localitycode'=>'LocalityCode',
            'parish' => 'SubLocality',
            'avenue' => 'Street',
            'preaven' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'description'=>'Description',
            //        'areacode'=>'PrePhone',
//            'tel' => 'TelephoneNo'
        ],
        'Workshop' => [
            'activity_name' => 'Name',
            'activity_type' => 'Activity',
        ],
        'ValidateTelephone' => [
            'tel' => 'Value'
        ],
        'ValidatePostCode' => [
            'postalcode' => 'Value'
        ],
        'BuildingUnits' => [
            'unit' => 'Units'
        ],
        'AddressString' => [
            'address' => 'Value'
        ],
        'ActivityCode' => [
            'activity_type1' => 'TypeCode',
            'activity_type2' => 'TypeCode2',
            'activity_type3' => 'TypeCode3'

        ],
        'AccuratePosition' => [
            'st_x' => 'Lon',
            'st_y' => 'Lat'
        ],
        'EstimatedPosition' => [
            'st_x' => 'Lon',
            'st_y' => 'Lat'
        ]

    ];
    public static $composite_database_fields = [
        'Position' => ['ST_X(geom),ST_Y(geom)']
        ,
        'Telephones' => [
            'tel',
            'areacode'
        ],
        'Postcode' => [
            'postalcode'
        ],
        'Address' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            //added
            'locationtype',
            'locationname',
//            'localitycode',


            //till here
            'parish',

            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name'
//            'description',
        ],
        'AddressString' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaventypename',
            'avenuetypename',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'unit',
            'building_name'
        ],
        'AddressAndTelephones' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
//            'localitycode',

            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name',
            //'description'

//            'areacode'
//            'tel'
        ],
        'AddressAndPostcode' => [
            'postalcode',
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
//            'localitycode',
            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name',
//            'description',
//        'areacode'
//        'tel',

        ],
        'Workshop' => [
            'activity_name',
            'activity_type'
        ],
        'ValidateTelephone' => [
            'tel'
        ],
        'ValidatePostCode' => [
            'postalcode'
        ],
        'BuildingUnits' => [
            'unit'
        ],
        'ActivityCode' => [
            'activity_type1',
            'activity_type2',
            'activity_type3',

        ],
        'AccuratePosition' => [
            'ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))'
        ],
        'EstimatedPosition' => [
            'ST_X(geom),ST_Y(geom)'
        ]
    ];
    public static $output_attrs = [];


    public static function serach($input_alias, $output_alias, $values, $input,$invalid_values)
    {

        $a = collect($values)->pluck(Constant::INPUTM[$input])->all();
        $query_field = array_diff($a,$invalid_values);
        $out_fields = self::createDatabaseFields($input, $output_alias);
        $output_result = self::createResponseFields($input, $output_alias);
        if ($input_alias == "tel") {
            $result = Post::searchInArray($input_alias, $query_field, $out_fields);
        } else {
            $result = Post::search($input_alias, $query_field, $out_fields);
        }

//        $result = [
//            '8000' => [
//                'postalcode' => 'TypeCode2',
//               ],
//            '883' => [
//                'postalcode' => 'TypujlCode2',
//            ],
//
//
//        ];
//        $result = [
//            '8000013786' => [
//                'unit' => 'tt',
//                'postalcode' => 'TypeCode2',
//               ],
//
////        ];
//        $result = [
//            '1' => [
//                'activity_type1' => 'TypeCode',
//                'activity_type2' => 'TypeCode2',
//                'activity_type3' => 'TypeCode3']
//        ];
//        $result = [
//            '8913613747' => [
//                'postalcode' => '8913613747',
//                'tel' => 'dcdsc',
//                'areacode' => 'dsc',
//            ]
//        ];
//        $result = [
//            '1' => [
//                'tel' => 'ph',
//
//            ],
//            '222' => [
//                'tel' => 'hh',
//
//            ],
//
//        ];
//        $result = [
//            '222' => [
//                'st_x'=>1,
//                'st_y'=>'lll',
//
//            ],
//            '1' => [
//                'st_x'=>1,
//                'st_y'=>'lll',
//
//            ]
//        ];
//        $result = [
//            '222' => [
//                'activity_type'=>1,
//                'activity_name'=>'lll',
//
//            ],
//            '1' => [
//                'activity_type'=>1,
//                'activity_name'=>'lll',
//
//            ]
//        ];
//        $result = [
//            '222' => [
//                'activity_type1'=>1,
//                'activity_type2'=>1,
//                'activity_type3'=>1,
//
//            ],
//            '1' => [
//                'activity_type1'=>1,
//                'activity_type2'=>1,
//                'activity_type3'=>1,
//
//            ]
//        ];
//        $result = [
//            '8000' => [
//                                'postalcode' => 'TypeCode2',
//
//                'statename' => 'Province',
//                'townname' => 'TownShip',
//                'zonename' => 'Zone',
//                'villagename' => 'Village',
//                'locationtype' => 'LocalityType',
//                'locationname' => 'LocalityName',
////            'localitycode'=>'LocalityCode',
//                'parish' => 'SubLocality',
//                'avenue' => 'Street',
//                'preaven' => 'Street2',
//                'plate_no' => 'HouseNumber',
//                'floorno' => 'Floor',
//                'unit' => 'SideFloor',
//                'building_name' => 'BuildingName',
////            'description'=>'Description',
////        'areacode'=>'PrePhone',
////            'tel' => 'TelephoneNo'
//            ],
//            '883' => [
//                                'postalcode' => 'TypeCode2',
//
//                'statename' => 'Province',
//                'townname' => 'TownShip',
//                'zonename' => 'Zone',
//                'villagename' => 'Village',
//                'locationtype' => 'LocalityType',
//                'locationname' => 'LocalityName',
////            'localitycode'=>'LocalityCode',
//                'parish' => 'SubLocality',
//                'avenue' => 'Street',
//                'preaven' => 'Street2',
//                'plate_no' => 'HouseNumber',
//                'floorno' => 'Floor',
//                'unit' => 'SideFloor',
//                'building_name' => 'BuildingName',
////            'description'=>'Description',
////        'areacode'=>'PrePhone',
////            'tel' => 'TelephoneNo'
//
//            ]
//        ];//pak shavad
        if (isset($result)) {
            return ServicesResponse::makeResponse($input,$result, $input_alias, $output_alias, $values, $output_result, $invalid_values);
        } else {
            throw new ServicesException($values,$input,$invalid_values);
        }
    }


    public static function createDatabaseFields($input, $name)
    {
        $activity_code = str_contains($name, "ActivityCode");
        $validate = str_contains($name, 'Validate');
        $name = array_key_exists($name,self::$composite_database_fields) ? self::$composite_database_fields[$name] : $name;
        if ($input == 'Postcode'
            && !$activity_code
            && !$validate) {
            $name [] = 'postalcode';

        }
//        dd($name);

        return $name;

    }

    public static function createResponseFields($input, $name)
    {
        $activity_code = str_contains($name, "ActivityCode");
        $validate = str_contains($name, 'Validate');

        $name = in_array($name, array_keys(self::$composite_response)) ? self::$composite_response[$name] : $name;
        if ($input == 'Postcode'
            && !$activity_code
            && !$validate) {
            $name ['postalcode'] = 'PostCode';
        }
//        dd($input, $name);
        return $name;

    }
}
