<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\PopulationPoint;
use App\Models\Post;
use App\Exceptions\ServicesException;
use App\Modules\AppRegistration;
use App\Modules\GavahiPdf;
use App\Modules\Payment;
use App\Modules\TaskManager;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;

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
        ],
        'Certification' => [
            'link' => 'link'
        ],
        'PostcodeByParcel' => [
            'postalcode' => 'PostCode'
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
        'AddressByCertificateNo' => [
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
        'PostcodeByParcel' => [
            'postalcode'
        ]
    ];
    public static $output_attrs = [];

    public static function auth($data)
    {
        $appreg = new AppRegistration();
        $user_token = $appreg->userToken($data);
        $myself = $appreg->myself($user_token);
        return [
            'access_token' => $myself['api_key'],
            'token_type' => 'bearer',
            'expires_in' => $myself['expires_in']
        ];

    }

    public static function serach($input_alias, $output_alias, $values, $input, $invalid_values, $scopes, $user_id)
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
        } elseif ($input_alias == "postalcode" && $output_alias == 'ReqStatus') {
            //todo call the model related to address verification
        } elseif ($input_alias == "postalcode") {
            $result = Post::search($input_alias, $query_field, $out_fields, $action_areas);
        }
        if ($output_alias == 'GenerateCertificate') {
            $gavahi_info = GavahiPdf::getLinkAndBarcode($query_field, $user_id, null, $values, $input, $invalid_values);
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
        dd($name);
        return $name;

    }

    public static function requestPostCode($data, $user_id, $input)
    {
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'TransactionID' => $data['TransactionID']
        ];
        $tracking_code = Payment::getTrackingCode($data['TransactionID'], $values, $input);
        $action_areas = PopulationPoint::getActionAreas($data['localityCode']);
        $task_manager_params = self::createTaskManagerParams($data, $tracking_code, $user_id, $action_areas);
        $status = TaskManager::createPostCodeTask($task_manager_params, $values, $input, $user_id);
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


    public static function createTaskManagerParams($data, $tracking_code, $reporter_id, $action_areas)
    {
        $params ["task_type_id"] = 6;
        $params["reporter"] = $reporter_id;
        if ($action_areas[0]['province_id']) {
            $params["action_area"]["province"]["id"] = $action_areas[0]['province_id'];
        }
        if ($action_areas[0]['province'] && $action_areas[0]['province']['name']) {
            $params["action_area"]["province"]["name"] = $action_areas[0]['province']['name'];
        }
        if ($action_areas[0]['county_id']) {
            $params["action_area"]["county"]["id"] = $action_areas[0]['county_id'];
        }
        if ($action_areas[0]['county'] && $action_areas[0]['county']['name']) {
            $params["action_area"]["county"]["name"] = $action_areas[0]['county']['name'];
        }
        if ($action_areas[0]['zone_id']) {
            $params["action_area"]["zone"]["id"] = $action_areas[0]['zone_id'];
        }
        if ($action_areas[0]['zone'] && $action_areas[0]['zone']['name']) {
            $params["action_area"]["zone"]["name"] = $action_areas[0]['zone']['name'];
        }
        if ($action_areas[0]['rural_id']) {
            $params["action_area"]["rural"]["id"] = $action_areas[0]['rural_id'];
        }
        if ($action_areas[0]['rural'] && $action_areas[0]['rural']['name']) {
            $params["action_area"]["rural"]["name"] = $action_areas[0]['rural']['name'];
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
        return $params;
    }

    public static function trackRequest($data, $input, $user_id)
    {
        $code = 0;
        $msg = trans('messages.custom.success.ResMsg');
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'FollowUpCode' => $data['FollowUpCode']
        ];
        $task = TaskManager::getTask($data['FollowUpCode'], $values, $input, $user_id);
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

    public static function generateCertificateByTxn($data, $user_id, $input, $invalid_values, $output_alias, $scopes, $input_alias)
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
            $gavahi_info = GavahiPdf::getLinkAndBarcode($postcodes, $user_id, $tracking_code, $values, $input, $invalid_values);
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

    public static function AddressByCertificateNo($data, $user_id, $input, $input_alias, $output_alias)
    {
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'CertificateNo' => $data['CertificateNo']
        ];
        $link = GavahiPdf::AddressByCertificateNo($data, $user_id, $values, $input);
        $output_result = self::createResponseFields($input, $output_alias);

        $info[$data['CertificateNo']]['link'] = $link['data']['link'];
        return ServicesResponse::makeResponse($input, $info, $input_alias, $output_alias, $values, $output_result, []);

    }

    public static function postcodeByParcel($values, $invalid_values, $input, $output_alias, $scopes)
    {

        $query_field = array_udiff_assoc($values['Parcels'], $invalid_values, function ($a, $b) {
            if ($a['Latitude'] != $b['Latitude'] && $a['Longitude'] != $b['Longitude']) return $a;
        });
        $out_fields = self::createDatabaseFields($input, $output_alias);
        $output_result = self::createResponseFields($input, $output_alias);
        $action_areas = null;
        if ($scopes) {
            $action_areas = $scopes['action_areas'];
        }

        $result = Post::search($input_alias, $query_field, $out_fields, $action_areas);

        if (isset($result)) {

            return ServicesResponse::makeResponse($input, $result, $input_alias, $output_alias, $values, $output_result, $invalid_values);
        } else {
            throw new ServicesException($values, $input, $invalid_values);
        }

    }


}
