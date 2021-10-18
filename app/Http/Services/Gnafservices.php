<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\Post;
use App\Exceptions\ServicesModelNotFoundException;

class Gnafservices
{

//        fields that is set as array in db!
//    public static $farsi = [
//        'statename' => 'استان',
//        'townname' => 'شهرستان',
//        'zonename' => 'بخش',
//        'villagename' => 'دهستان',
////        'locationtype' => 'شهر/روستا/آبادی',
////        'locationname' => 'localityname',
//        'parish' => 'محله',
//        'preaven' => 'خیابان',
//        'avenue' => 'خیابان',
//        'plate_no' => 'پلاک',
//        'floorno' => 'طبقه',
////        'building_name' => 'building_name'
//    ];


    public static $composite_response = [
        'Position' => [
            'st_x' => 'Latitude',
            'st_y' => 'Longitude'
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
            'st_x' => 'Lat',
            'st_y' => 'Lon'
        ],
        'EstimatedPosition' => [
            'st_x' => 'Lat',
            'st_y' => 'Lon'
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


    public static function serach($inp, $input, $output, $values, $out_fields, $invalid_inputs)
    {
        $query_field = collect($values)->pluck($inp)->all();
        $output_result = self::createResponseFields($input, $output);
//        dd( $output);
        if ($input == "tel") {
            $result = Post::searchInArray($input, $query_field, $out_fields);
        } else {
            $result = Post::search($input, $query_field, $out_fields);
//            dd($result);
        }
        $data = array();
        $area_code = '';
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
//        dd($result);
        if (isset($result)) {
            return ServicesResponse::makeResponse($result, $inp, $input, $output, $values, $output_result, $invalid_inputs);
            //no result
        } else {
            foreach ($values as $PorT) {
                $area_code = '';
                $temp = $PorT[$inp];
                $client_row_id = $PorT['ClientRowID'];
                if (isset($PorT['AreaCode'])) {
                    $area_code = $PorT['AreaCode'];
                }
                //toDo Exception models not found or invalid input
                $data[$temp] = ServicesResponse::recordNotFound($client_row_id, $temp, $area_code, $input, $inp, $invalid_inputs);
            }

            return [
                "ResCode" => Constant::ERROR_RESPONSE_CODE,
                "ResMsg" => trans('messages.custom.error.ResMsg'),
                "Data" => array_values($data)
            ];
//
        }
    }


    public static function createDatabaseFields($input, $name)
    {
//        dd($input, $name);
        $activity_code = str_contains($name, "ActivityCode");
        $validate = str_contains($name, 'Validate');
        $name = in_array($name, array_keys(self::$composite_database_fields)) ? self::$composite_database_fields[$name] : $name;
        if ($input == 'Postcode'
            && !$activity_code
            && !$validate) {
            $name [] = 'postalcode';

        }
//        dd($name);

        return $name;

    }

    public
    static function createResponseFields($input, $name)
    {
//        dd($input,$name);
        $activity_code = str_contains($name, "ActivityCode");
        $validate = str_contains($name, 'Validate');

        $name = in_array($name, array_keys(self::$composite_response)) ? self::$composite_response[$name] : $name;
        if ($input == 'postalcode'
            && !$activity_code
            && !$validate) {
            $name ['postalcode'] = 'PostCode';
        }
//        dd($input, $name);
        return $name;

    }

    public
    static function makeAddressString($v)
    {
//        dd($v);
        $result = "";
        //state
        if (array_key_exists('statename', $v) && $v['statename']) {
            $result .= 'استان ';
            $result .= $v['statename'];
            $result .= '، ';
        }
        //town
        if (array_key_exists('townname', $v) && $v['townname']) {
            $result .= 'شهرستان ';
            $result .= $v['townname'];
            $result .= '، ';
        }
        //zone
        if (array_key_exists('zonename', $v) && $v['zonename']) {
            $result .= 'بخش ';
            $result .= $v['zonename'];
            $result .= '، ';
        }
        //location
        if (array_key_exists('locationtype', $v)
            && array_key_exists('locationname', $v)) {


            if ($v['locationtype'] == 'شهر' &&
                $v['locationname']) {

                $result .= 'شهر ';
                $result .= $v['locationname'];
                $result .= '، ';
            } elseif ($v['locationtype'] == 'روستا' &&
                $v['locationname']) {

                $result .= 'روستای ';
                $result .= $v['locationname'];
                $result .= '، ';
            } elseif ($v['locationtype'] == 'آبادی' &&
                $v['locationname']) {

                $result .= 'آبادی ';
                $result .= $v['locationname'];
                $result .= '، ';
            }
        }
//parish
        if (array_key_exists('parish', $v)) {
            if ($v['parish']) {
                $result .= $v['parish'];
                $result .= '، ';
            }
        }
//preavenue avenue
        if (
            array_key_exists('preaventypename', $v)
            && array_key_exists('preaven', $v)
            && array_key_exists('avenue', $v)
            && array_key_exists('avenuetypename', $v)
        ) {
            if ($v['preaventypename'] ||
                $v['preaven']) {
                $result .= $v['preaventypename'];
                $result .= ' ';
                $result .= $v['preaven'];
                $result .= '، ';

            }
            if ($v['avenuetypename'] ||
                $v['avenue']) {
                $result .= $v['avenuetypename'];
                $result .= ' ';
                $result .= $v['avenue'];
                $result .= '، ';

            }

        }
//        plateno
        if (array_key_exists('plate_no', $v)
            && $v['plate_no']) {
            $result .= 'پلاک ';
            $result .= $v['plate_no'];
            $result .= '، ';
        }
        //building_name
        if (array_key_exists('building_name', $v)
            && $v['building_name']) {
            $result .= $v['building_name'];
            $result .= '، ';
        }
//        floor
        if (array_key_exists('floorno', $v)) {
            $result .= 'طبقه ';
            $result .= ((int)$v['floorno'] == 0) ?
                'همکف' : $v['floorno'];
            $result .= '، ';
        }


//        unit
        if (array_key_exists('unit', $v)
            && $v['unit']) {
            $result .= 'واحد ';
            $result .= $v['unit'];
//            $result .= '، ';
        }

        return $result;
    }
}
