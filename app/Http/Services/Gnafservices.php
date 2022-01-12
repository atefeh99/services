<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\Post;
use App\Exceptions\ServicesException;
use App\Modules\GavahiPdf;

class Gnafservices
{

    public static $composite_response = [
        'Position' => [
            'st_x' => 'Longitude',
            'st_y' => 'Latitude'
        ]
        ,
        'Telephones' => [
            'tels' => 'SubscriberNumber',
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
            'population_point_id' => 'LocalityCode',
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
            'population_point_id' => 'LocalityCode',
            'parish' => 'SubLocality',
            'avenue' => 'Street',
            'preaven' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'description'=>'Description',
            'tels' => 'TelephoneNo'

        ],
        'AddressAndPostcode' => [
            'postalcode' => 'PostCode',
            'statename' => 'Province',
            'townname' => 'TownShip',
            'zonename' => 'Zone',
            'villagename' => 'Village',
            'locationtype' => 'LocalityType',
            'locationname' => 'LocalityName',
            'population_point_id' => 'LocalityCode',
            'parish' => 'SubLocality',
            'avenue' => 'Street',
            'preaven' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'description'=>'Description',
        ],
        'Workshop' => [
            'activity_name' => 'Name',
            'activity_type' => 'Activity',
        ],
        'ValidateTelephone' => [
            'tels' => 'Value'
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
            'activity_type' => 'TypeCode',
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
        ],
        'GenerateCertificate' => [

            'statename' => 'Province',
            'townname' => 'TownShip',
            'zonename' => 'Zone',
            'villagename' => 'Village',
            'locationtype' => 'LocalityType',
            'locationname' => 'LocalityName',
            'population_point_id' => 'LocalityCode',
            'parish' => 'SubLocality',
            'avenue' => 'Street',
            'preaven' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'CertificateUrl' => 'CertificateUrl',
            'CertificateNo' => 'CertificateNo',
            //            'description'=>'Description',


        ]

    ];
    public static $composite_database_fields = [
        'Position' => [
            'ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))',
            'province_id',
            'tels'

        ]
        ,
        'Telephones' => [
            'tels',
            'province_id',
        ],
        'Postcode' => [
            'postalcode',
            'province_id',
            'tels'
        ],
        'Address' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'population_point_id',
            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name',
//            'description',
            'province_id',
            'tels'

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
            'building_name',
            'province_id'

        ],
        'AddressAndTelephones' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'population_point_id',
            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name',
            'province_id',
            //'description'
            'tels',

        ],
        'AddressAndPostcode' => [
            'postalcode',
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'population_point_id',
            'parish',
            'avenue',
            'preaven',
            'plate_no',
            'floorno',
            'unit',
            'building_name',
            'province_id',
//            'description',
        'tels',

        ],
        'Workshop' => [
            'activity_name',
            'activity_type',
            'province_id',
            'tels'

        ],
        'ValidateTelephone' => [
            'tels',
            'province_id'

        ],
        'ValidatePostCode' => [
            'postalcode',
            'province_id'

        ],
        'BuildingUnits' => [
            'unit',
            'province_id'

        ],
        'ActivityCode' => [
            'activity_type',
//            'activity_type2',
//            'activity_type3',
            'province_id',
            'tels'


        ],
        'AccuratePosition' => [
            'ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))',
            'province_id'

        ],
        'EstimatedPosition' => [
            'ST_X(geom),ST_Y(geom)',
            'province_id'

        ],
        'GenerateCertificate' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'population_point_id',
            'parish',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'unit',
            'province_id'
            //description

        ]
    ];
    public static $output_attrs = [];


    public static function serach($input_alias, $output_alias, $values, $input, $invalid_values, $scopes)
    {
        $a = collect($values)->pluck(Constant::INPUTM[$input])->all();
        $query_field = array_diff($a, $invalid_values);
        $out_fields = self::createDatabaseFields($input, $output_alias);
        $output_result = self::createResponseFields($input, $output_alias);
//dd($input_alias, $output_alias, $values, $input, $invalid_values, $scopes);
        $action_areas = null;
        if ($scopes) {
            $action_areas = $scopes['action_areas'];
        }
        if ($input_alias == "tels") {
            $result = Post::searchInArray($input_alias, $query_field, $out_fields, $action_areas);
//            dd($result);
        } else {
            $result = Post::search($input_alias, $query_field, $out_fields, $action_areas);
        }
        if ($output_alias == 'GenerateCertificate') {
            $gavahi_info = GavahiPdf::getLinkAndBarcode($query_field, $values, $input, $invalid_values);
            if ($gavahi_info['link']) {
                foreach ($result as $k => $r) {
                    $result[$k]['CertificateUrl'] = $gavahi_info['link'];
                    $result[$k]['CertificateNo'] = $gavahi_info['extra_info'][$k]['barcode'];
                }
            } else {
                $msg = trans('messages.custom.error.msg_part1');
                throw new ServicesException($values, $input, $invalid_values, 9070, $msg, null);
            }
        }

        if (isset($result)) {

            return ServicesResponse::makeResponse($input, $result, $input_alias, $output_alias, $values, $output_result, $invalid_values);
        } else {
            throw new ServicesException($values, $input, $invalid_values);
        }
    }


    public static function createDatabaseFields($input, $name)
    {
        $activity_code = str_contains($name, "ActivityCode");
        $validate = str_contains($name, 'Validate');
        $name = array_key_exists($name, self::$composite_database_fields) ? self::$composite_database_fields[$name] : $name;
        if ($input == 'Postcode'
//            && !$activity_code
            && !$validate) {
            $name [] = 'postalcode';

        }

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
