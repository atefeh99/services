<?php

namespace App\Http\Services;

use App\Models\Post;

class Gnafservices
{

//        fields that is set as array in db!
    public static $farsi = [
        'statename' => 'استان',
        'townname' => 'شهرستان',
        'zonename' => 'بخش',
        'villagename' => 'دهستان',
//        'locationtype' => 'شهر/روستا/آبادی',
//        'locationname' => 'localityname',
        'parish' => 'محله',
        'preaven' => 'خیابان',
        'avenue' => 'خیابان',
        'plate_no' => 'پلاک',
        'floorno' => 'طبقه',
//        'building_name' => 'building_name'
    ];


    public static $composite_response = [
        'Position' => [
            'st_x' => 'Latitude',
            'st_y' => 'Longitude'
        ]
        ,
        'Telephones' => [
            'tel' => 'SubscriberNumber'
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
            'tel'
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
//            'locationtype',
//            'locationname',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
//            'unit',
//            'building_name'
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


    public static function serach($inp, $input, $output, $values, $out_fields)
    {
        $query_field = collect($values)->pluck($inp)->all();
        $output_result = self::createResponseFields($input, $output);
//        dd( $output_result);
        if ($input == "tel") {
            $result = Post::searchInArray($input, $query_field, $out_fields);
        } else {
            $result = Post::search($input, $query_field, $out_fields);
        }
        $data = array();
        $area_code = '';
//        $result = [
//            '1' => [
//                'tel' => 'ph',
//
//            ],
//            '222' => [
//                'tel' => 'hh',
//
//            ],

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
//            '222' => [
//                'postalcode' => 'lkll',
//                'statename' => '999',
//                'townname' => 'dd',
//                'zonename' => 'pp',
//                'villagename' => '',
//                'locationtype' => '',
//                'locationname' => '',
//                'parish' => '',
//                'avenue' => '',
//                'preaven' => '',
//                'plate_no' => 'sxa',
//                'floorno' => 'll',
//                'unit' => 'sd',
//                'building_name' => 'll',
//
//            ],
//            '1' => [
//                'postalcode' => 'lkll',
//                'statename' => '999',
//                'townname' => 'dd',
//                'zonename' => 'pp',
//                'villagename' => '',
//                'locationtype' => '',
//                'locationname' => '',
//                'parish' => '',
//                'avenue' => '',
//                'preaven' => '',
//                'plate_no' => 'sxa',
//                'floorno' => 'll',
//                'unit' => 'sd',
//                'building_name' => 'll',
//
//            ]
//        ];//pak shavad
//        dd($result);
        if (isset($result)) {
            //loop through postcodes or telephones
            foreach ($values as $PorT) {
//                dd($PorT);
                //check if the postcode has data or not
                $temp = $PorT[$inp];
                $client_row_id = $PorT['ClientRowID'];
                if (isset($PorT['AreaCode'])) {
                    $area_code = $PorT['AreaCode'];
                }
                if (array_key_exists($temp, $result)) {
                    $data[$temp] = [
                        'ClientRowID' => $client_row_id,
                    ];
                    if ($input == "tel") {
                        $data[$temp] += [
                            'AreaCode' => $area_code];
                    }
                    $data[$temp] += [

                        $inp => $temp,
                        'Succ' => true,
                    ];


                    if ($output == "AddressString") {
                        foreach ($result as $k => $v) {
                        $address_string =self::makeAddressString($v, $temp);
                            $data[$temp]['Result']= [
                                'Value' => $address_string,
                                "PostCode" => $k,
                                'TraceID' => "",
                                'ErrorCode' => 0,
                                'ErrorMessage' => null
                            ];
                        }
                    } else {
                        //loop through postalcode or telephones
                        foreach ($result as $k => $v) {
//
                            //loop through attributes
                            foreach ($v as $key => $value) {
                                //change the keys when we have result
                                $key1 = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
                                $attribute = $result[$k][$key];
                                if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                                    $attribute = 'true';
                                }

                                unset($result[$k][$key]);
                                $result[$k][$key1] = $attribute;
                                $data[$temp]['Result'] = $result[$k];
                                $data[$temp]['Result'] += [
                                    'TraceID' => "",
                                    'ErrorCode' => 0,
                                    'ErrorMessage' => null
                                ];
                            }

                        }
                    }
                    $data[$temp] += [
                        'Errors' => null,
                    ];
//no data for the specific postcode or tel
                } else {
                    $data[$temp] = [
                        'ClientRowID' => $client_row_id,
                    ];
                    if ($input == "tel") {
                        $data[$temp] += [
                            'AreaCode' => $area_code];
                    }
                    $data[$temp] += [
                        $inp => $PorT[$inp],
                        'Succ' => false,
                        'Result' => null,
                        'Errors' => [
                            'ErrorCode' => "",
                            'ErrorMessage' => ""
                        ]
                    ];

                }
            }
            return [
                "ResCode" => 0,
                "ResMsg" => trans('messages.custom.success.ResMsg'),
                "Data" => array_values($data)
            ];
            //if no data available for all postcodes
        } else {

            foreach ($values as $PorT) {

                $temp = $PorT[$inp];
                $client_row_id = $PorT['ClientRowID'];
                $data[$temp] = [
                    'ClientRowID' => $client_row_id,
                ];
                if (isset($PorT['AreaCode'])) {
                    $area_code = $PorT['AreaCode'];
                    $data[$temp] += [
                        'AreaCode' => $area_code];
                }
                $data[$temp] += [
                    $inp => $temp,
                    'Succ' => false,
                    'Result' => null,
                    'Errors' => [
                        'ErrorCode' => "",
                        'ErrorMessage' => ""
                    ]
                ];
            }
            return [
                "ResCode" => "",
                "ResMsg" => trans('messages.custom.error.ResMsg'),
                "Data" => array_values($data)
            ];
        }
    }


    public static function createDatabaseFields($input, $name)
    {
//        dd($input, $name);
        $name = in_array($name, array_keys(self::$composite_database_fields)) ? self::$composite_database_fields[$name] : $name;
        if ($input == 'Postcode'/* && $name !== 'validate' ToDo*/) {
            $name [] = 'postalcode';
        }

        return $name;

    }

    public static function createResponseFields($input, $name)
    {
//        dd($input,$name);

        $name = in_array($name, array_keys(self::$composite_response)) ? self::$composite_response[$name] : $name;
        if ($input == 'postalcode'/* && $name !== 'validate' ToDo*/) {
            $name ['postalcode'] = 'PostCode';
        }
//        dd($input, $name);
        return $name;

    }

    public static function makeAddressString($v,$temp)
    {
        $result = "";
            foreach ($v as $kelid => $meghdar) {
                if ($kelid != 'postalcode') {

//                    $address_string .= array_key_exists($kelid, self::$farsi) ? self::$farsi[$kelid] : $kelid;
//                    $address_string .= ' ' . $meghdar . ',';
                    $flag = false;
                    if (array_key_exists('statename', $v)) {
                        $result = 'استان ';
                        $result .= $this->attributes['statename'];
                        $result .= '، ';
                    }
//        city
                    if (array_key_exists('locationtype', $this->attributes)
                        && array_key_exists('locationname', $this->attributes)) {
                        if ($this->attributes['locationtype'] == 'شهر' &&
                            $this->attributes['locationname']) {

                            $result .= 'شهر ';
                            $result .= $this->attributes['locationname'];
                            $result .= '، ';
                        }
                    }

//        parish
                    if (array_key_exists('parish', $this->attributes)
                        && array_key_exists('tour', $this->attributes)) {
                        if ($this->attributes['parish']) {
                            $result .= $this->attributes['parish'];
                        }
                        if ($this->attributes['parish'] && $this->attributes['tour']) {
                            $result .= '/';
                        }
                        if ($this->attributes['tour']) {
                            $result .= $this->attributes['tour'];
                        }
                        if ($this->attributes['parish'] || $this->attributes['tour']) {
                            $result .= '، ';
                        }
                    }

                    if (
                        array_key_exists('preaventypename', $this->attributes)
                        &&  array_key_exists('preaven', $this->attributes)
                        && array_key_exists('avenue', $this->attributes)
                        && array_key_exists('avenuetypename', $this->attributes)
                    ) {
                        if ($this->attributes['preaventypename'] ||
                            $this->attributes['preaven']) {
                            $result .= $this->attributes['preaventypename'];
                            $result .= ' ';
                            $result .= $this->attributes['preaven'];
                        }
                        if (($this->attributes['preaventypename'] ||
                                $this->attributes['preaven']) && (
                                $this->attributes['avenuetypename'] ||
                                $this->attributes['avenue']
                            )) {
                            $result .= '/';
                        }
                        if ($this->attributes['avenuetypename'] ||
                            $this->attributes['avenue']) {
                            $result .= $this->attributes['avenuetypename'];
                            $result .= ' ';
                            $result .= $this->attributes['avenue'];
                        }
                        if (($this->attributes['preaventypename'] ||
                                $this->attributes['preaven']) || (
                                $this->attributes['avenuetypename'] ||
                                $this->attributes['avenue']
                            )) {
                            $result .= '، ';
                        }
                    }
//        plateno
                    if (array_key_exists('pelak', $this->attributes)
                        && $this->attributes['pelak']) {
                        $result .= 'پلاک ';
                        $result .= $this->attributes['pelak'];
                        $result .= '، ';
                    }
//        floor
                    if (array_key_exists('floorno', $this->attributes)) {
                        $result .= 'طبقه ';
                        $result .= ((int)$this->attributes['floorno']==0)?
                            'همکف':$this->attributes['floorno'];
                        $result .= '، ';
                    }


//        unit
                    if (array_key_exists('unit', $this->attributes)
                        && $this->attributes['unit']) {
                        $result .= 'واحد ';
                        $result .= $this->attributes['unit'];
                        $result .= '، ';
                    }


//        postalcode
                    if (array_key_exists('postalcode', $this->attributes)
                        && $this->attributes['postalcode']) {
                        $result .= 'کد پستی:';
                        $result .= $this->attributes['postalcode'];
                    }


                }
            }



}
