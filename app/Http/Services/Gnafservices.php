<?php

namespace App\Http\Services;

use App\Models\Post;

class Gnafservices
{

//        fields that is set as array in db!
    public static $farsi = [
        'statename' => 'استان',
        'townname' => '',
        'zonename' => 'بخش',
//        'villagename' => 'village',
//        'locationtype' => 'localitiytype',
//        'locationname' => 'localityname',
        'parish' => 'محله',
        'preaven' => 'خیابان',
        'avenue' => 'خیابان',
        'plate_no' => 'پلاک',
        'floorno' => 'طبقه',
//        'building_name' => 'building_name'
    ];


    public static $composite_result = [
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
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'plate_no' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name'

        ],
        'AddressAndTelephones' => [
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'plate_no' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name',
            'tel' => 'TelephoneNo'

        ],
        'AddressAndPostcode' => [
            'postalcode' => 'Postcode',
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'plate_no' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name',
        ],
        'Workshop' => [
            'activity_type' => 'Activity',
            'activity_name' => 'Name'
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
        ]

    ];
    public static $composite_output = [
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
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'building_name'
        ],
        'AddressString' => [
            'statename',
            'townname',
            'zonename',
//            'villagename',
//            'locationtype',
//            'locationname',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
//            'building_name'
        ],
        'AddressAndTelephones' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'building_name',
            'tel'
        ],
        'AddressAndPostcode' => [
            'postalcode',
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'building_name',

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
        ]
    ];
    public static $output_attrs = [];


    public static function handlingField($inp, $input, $output, $values, $out_fields)
    {
        $query_field = collect($values)->pluck($inp)->all();
        $output_result = self::createResultFields($input, $output);
        if ($input == "tel") {
            $result = Post::searchinarray($input, $query_field, $out_fields);
        } else {
            $result = Post::search($input, $query_field, $out_fields);
        }
        dd($result);
        $data = array();
        if (isset($result)) {
            //loop through postcodes or telephones
            foreach ($values as $PorT) {
                dd($PorT);
                //check if the postcode has data or not
                $temp = $PorT[$inp];
                $client_row_id = $PorT['ClientRowID'];

                if (array_key_exists($temp, $result)) {
                    $data[$temp] = [
                        'Errors' => null,
                        $inp => $temp,
                        'ClientRowID' => $client_row_id,
                        'Succ' => true,
                    ];
                    if ($output == "AddressString") {

                        foreach ($result as $k => $v) {
                            $address_string = "";
                            foreach ($v as $kelid => $meghdar) {
                                if ($kelid != 'postalcode') {
                                    $address_string .= array_key_exists($kelid, self::$farsi) ? self::$farsi[$kelid] : $kelid;
                                    $address_string .= ' ' . $meghdar . ',';
                                }
                            }
                            $data[$temp]['Result'] = [
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
                            if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                                $result[$k]['Value'] = "true";
                            }
                            //loop through attributes
                            foreach ($v as $key => $value) {
                                //change the keys when we have result
                                $key1 = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
                                $attribute = $result[$k][$key];
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
//no data for the specific postcode
                } else {
                    $data[$temp] = [
                        'Result' => null,
                        $inp => $PorT[$inp],
                        'ClientRowID' => $client_row_id,
                        'Succ' => false,
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
                    'Result' => null,
                    $inp => $temp,
                    'ClientRowID' => $client_row_id,
                    'Succ' => false,
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


    public static function createOutFields($input, $name)
    {
//        dd($input,$name);
        $name = in_array($name, array_keys(self::$composite_output)) ? self::$composite_output[$name] : $name;
        if ($input == 'Postcode'/* && $name !== 'validate' ToDo*/) {
            $name [] = 'postalcode';
        }
        return $name;

    }

    public static function createResultFields($input, $name)
    {

        $name = in_array($name, array_keys(self::$composite_result)) ? self::$composite_result[$name] : $name;
        if ($input == 'postalcode'/* && $name !== 'validate' ToDo*/) {
            $name ['postalcode'] = 'PostCode';
        }
//        dd($input, $name);
        return $name;

    }
}
