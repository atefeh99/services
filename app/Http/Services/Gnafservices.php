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



    public static function handlingField($input, $output, $values, $out_fields)
    {

        $output_result = self::createresultFields($output);
        if ($input == "tel") {
            $result = Post::searchinarray($input, $values, $out_fields);
        } else {
            $result = Post::search($input, $values, $out_fields);
        }
        dd($result);


        if ($output == "AddressString") {
            $r = "";
            foreach ($result as $key => $value) {
                $r .= array_key_exists($key,self::$farsi) ? self::$farsi[$key] : $key;
                $r .= ' ' . $value . ' ,';
            }
            unset($result);
            $result['address'] = $r;
        }

        foreach ($result as $key => $value) {

            $key1 = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
            if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                $temp['Value'] = "true";
            } else {
                $temp[$key1] = $result[$key];
            }
        }

        $temp['ErrorCode'] = 0;
        $temp['ErrorMessage'] = null;
        $temp['TraceID'] = "";


        return $temp;
    }


    public static function  createOutFields($name)
    {
//        if (in_array($name, array_keys(self::$composite_output))) {
//            return array_map(function ($item) use ($name) {
//                return $this->createOutFields($item);
//            }, self::$composite_output[$name]);
//        } else {
//            self::$output_attrs[] = $name;
//            return $name;
//        }
        $name = in_array($name, array_keys(self::$composite_output)) ? self::$composite_output[$name] : $name;
        return $name;

    }

    public static function createresultFields($name)
    {
        $name = in_array($name, array_keys(self::$composite_result)) ? self::$composite_result[$name] : $name;
        return $name;

    }
}
