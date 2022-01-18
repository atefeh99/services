<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\Post;
use App\Exceptions\ServicesException;
use App\Modules\GavahiPdf;
use App\Modules\Payment;
use App\Modules\TaskManager;
use Illuminate\Support\Facades\Log;

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
            'building_name' => 'BuildingName'

            //            'description'=>'Description',


        ],
        'ReqStatus' => [
            'nationalcode' => 'NationalCode',
            'postalcode' => 'PostalCode',
            'statename' => 'Province',
            'townname' => 'City',
            'address' => 'Address',
            'tels' => 'Mob',
            'AcceptDateTime' => 'AcceptDateTime',
            'st_x' => 'Lon',
            'st_y' => 'Lat'
        ],
        'GenerateCertificateByTxn' => [

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
            'building_name' => 'BuildingName'
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
            'avenuetypename',
            'preaventypename',
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
            'avenuetypename',
            'preaventypename',
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
            'avenuetypename',
            'avenue',
            'preaventypename',
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
            'avenuetypename',
            'preaventypename',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'unit',
            'province_id',
            'building_name'

            //description

        ],
        'ReqStatus' => [
            'nationalcode',
            'postalcode',
            'statename',
            'townname',
            'address',
            'tels',
            'AcceptDateTime',
            'ST_X(ST_AsText(ST_Centroid(parcel))),ST_Y(ST_AsText(ST_Centroid(parcel)))'
        ],
        'GenerateCertificateByTxn' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'population_point_id',
            'parish',
            'avenuetypename',
            'preaventypename',
            'preaven',
            'avenue',
            'plate_no',
            'floorno',
            'unit',
            'province_id',
            'building_name'
            //description

        ],
    ];
    public static $output_attrs = [];


    public static function serach($input_alias, $output_alias, $values, $input, $invalid_values, $scopes,$user_id)
    {
        $result = [];
        $a = collect($values)->pluck(Constant::INPUTM[$input])->all();
        $query_field = array_diff($a, $invalid_values);
        $out_fields = self::createDatabaseFields($input, $output_alias);
        $output_result = self::createResponseFields($input, $output_alias);
        $action_areas = null;
        if ($scopes) {
            $action_areas = $scopes['action_areas'];
        }
        if ($input_alias == "tels") {
            $result = Post::searchInArray($input_alias, $query_field, $out_fields, $action_areas);
//            dd($result);
        } elseif ($input_alias == "postalcode" && $output_alias == 'ReqStatus') {
            //todo call the model related to address verification
        } elseif ($input_alias == "postalcode") {
            $result = Post::search($input_alias, $query_field, $out_fields, $action_areas);
        }
        if ($output_alias == 'GenerateCertificate') {
            $gavahi_info = GavahiPdf::getLinkAndBarcode($query_field, $user_id, $values, $input, $invalid_values);
            foreach ($result as $k => $r) {
                $result[$k]['CertificateUrl'] = $gavahi_info['link'];
                $result[$k]['CertificateNo'] = $gavahi_info['extra_info'][$k]['barcode'];
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

    public static function requestPostCode($data, $scopes, $user_id, $input)
    {
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'TransactionID' => $data['TransactionID']
        ];
        $tracking_code = Payment::getTrackingCode($data['TransactionID'], $values, $input);
        $task_manager_params = self::createTaskManagerParams($data, $scopes, $tracking_code, $user_id);
        $status = TaskManager::createPostCodeTask($task_manager_params, $values, $input,$user_id);
        if ($status['message'] == 'successfully created') {
            $msg = trans('messages.custom.success.ResMsg');
            $res_data = [
                "FollowUpCode" => $tracking_code,
                "Succ" => true,
                "Errors" => null
            ];
            return ServicesResponse::makeResponse2(0, $msg, $res_data);

        } else {
            $msg = trans('messages.custom.error.transaction_part1') . $data['TransactionID'] . trans('messages.custom.error.transaction_part2');
            throw new ServicesException(null, null, [], null, null, null, -35, $msg, 'empty');
        }
    }

    public static function createTaskManagerParams($data, $scopes, $tracking_code, $reporter_id)
    {
        $params ["task_type_id"] = 6;
        $params["reporter"] = $reporter_id;
        if (array_key_exists('province', $scopes['action_areas'])) {
            $params["action_area"]["province"]["id"] = $scopes['action_areas']['province'][0];
        }
        if (array_key_exists('city', $scopes['action_areas'])) {
            $params["action_area"]["city"]["id"] = $scopes['action_areas']['city'][0];
        }
        if (array_key_exists('county', $scopes['action_areas'])) {
            $params["action_area"]["county"]["id"] = $scopes['action_areas']['county'][0];
        }

        $params["unique_features"] = [
            "name" => $data['firstName'],
            "surname" => $data['lastName'],
            "user_mobile" => $data['mobileNo'],
            "phone_number" => $data["prePhoneNo"] . $data["phoneNo"],
            "detailed_address" => $data['address'],
            "detailed_address_geom" => [
                $data['lon'],
                $data['lat']
            ],
            "tracking_number" => $tracking_code,
            "nearest_postcode" => $data['nearestPostCode'],
            "units" => [
                [
                    "floor" => $data['floorNo'],
                    "unit" => $data['sideFloor'],
                    "activityType" => $data['landUse']
                ]
            ],
            "population_point_id" => $data['localityCode'],
            "email" => $data['email'],
            "plate_no" => $data['houseNo'],
            "province" => array_key_exists('unit', $data) ? $data['unit'] : null
        ];
        Log::info(print_r($scopes, TRUE));
        Log::info(print_r($params, TRUE));
        return $params;
    }

    public static function trackRequest($data, $input,$user_id)
    {
        $code = 0;
        $msg = trans('messages.custom.success.ResMsg');
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'FollowUpCode' => $data['FollowUpCode']
        ];
        $task = TaskManager::getTask($data['FollowUpCode'], $values, $input,$user_id);
        $state = $task['value'][0]['state'];

        $res_data = [
            "FollowUpCode" => $data['FollowUpCode'],
            "StatusCode" => $state,
            "Succ" => true,
            "Errors" => null
        ];
        if ($state == 'done') {
            $res_data["PostCode"] = $task['value'][0]['unique_features']['units'][0]['postcode'];;
        } elseif ($state == 'failed') {
            $code = 12;
            $msg = trans('messages.custom.error.ResMsg');
            $res_data = null;
        }
        return ServicesResponse::makeResponse2($code, $msg, $res_data);
    }

    public static function generateCertificateByTxn($data,$user_id, $input, $invalid_values, $output_alias, $scopes, $input_alias)
    {
        $values[0] = [
            "ClientRowID" => $data['ClientRowID'],
            "PostCode" => $data['PostCode']
        ];
        $tracking_code = Payment::getTrackingCode($data['TransactionID'], $values, $input);

        $postcodes[0] = $data['PostCode'];
        $action_areas = null;
        if ($scopes) {
            $action_areas = $scopes['action_areas'];
        }
        $out_fields = self::createDatabaseFields($input, $output_alias);
        $a = collect($values)->pluck(Constant::INPUTM[$input])->all();
        $query_field = array_diff($a, $invalid_values);

        if (!empty($tracking_code)) {
            $gavahi_info = GavahiPdf::getLinkAndBarcode($postcodes, $user_id ,$values, $input, $invalid_values);
            $result = Post::search($input_alias, $query_field, $out_fields, $action_areas);
            $result[$data['PostCode']]['CertificateUrl'] = $gavahi_info['link'];
            $result[$data['PostCode']]['CertificateNo'] = $gavahi_info['extra_info'][$data['PostCode']]['barcode'];
            $output_result = self::createResponseFields($input, $output_alias);
            return ServicesResponse::makeResponse($input, $result, null, $output_alias, $values, $output_result, $invalid_values);

        } else {
            //throw exception??
            Log::error('tacking code null');
        }

    }
}
