<?php

namespace App\Http\Services;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use App\Models\Building;
use App\Models\PopulationPoint;
use App\Models\Post;
use App\Exceptions\ServicesException;
use App\Modules\AppRegistration;
use App\Modules\GavahiPdf;
use App\Modules\Payment;
use App\Modules\Redis;
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
            'preaven' => 'Street',
            'avenue' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
            'entrance' => 'Description',
            'mainavenue' => 'MainAvenue'

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
            'preaven' => 'Street',
            'avenue' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'entrance' => 'Description',
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
            'preaven' => 'Street',
            'avenue' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'building_name' => 'BuildingName',
//            'entrance' => 'Description',
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
            'preaven' => 'Street',
            'avenue' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'CertificateUrl' => 'CertificateUrl',
            'CertificateNo' => 'CertificateNo',
            'building_name' => 'BuildingName',
            'entrance' => 'Description',


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
            'preaven' => 'Street',
            'avenue' => 'Street2',
            'plate_no' => 'HouseNumber',
            'floorno' => 'Floor',
            'unit' => 'SideFloor',
            'CertificateUrl' => 'CertificateUrl',
            'CertificateNo' => 'CertificateNo',
            'building_name' => 'BuildingName',
            'entrance' => 'Description',

        ],
        'Certification' => [
            'link' => 'link'
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
            'entrance',
            'province_id',
            'mainavenue',
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
            'province_id',
            'mainavenue',
            'entrance',
            'building_type'

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
//            'entrance',
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
//            'entrance',
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
            'building_name',
            'entrance'

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
            'building_name',
            'entrance'
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
            'building_name',
//            'entrance'
        ],
        'PostcodeByParcel' => [
            'postalcode'
        ]
    ];

    public static function auth($data)
    {
        $appreg = new AppRegistration();
        $user_token = $appreg->userToken($data);
        Log::info(__CLASS__ . ':' . __FUNCTION__ . ':user token:' . $user_token);
        $user_id = $appreg->validateTokenAndGetUserId($user_token);
        Log::info(__CLASS__ . ':' . __FUNCTION__ . ':user_id:' . $user_id);
        $myself = $appreg->myself($user_id, $user_token);
        Log::info('myself: ' . json_encode($myself));
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
        if (isset($result)) {
            if ($output_alias == 'GenerateCertificate') {
                $gavahi_info = GavahiPdf::getLinkAndBarcode($query_field, $user_id, null, $values, $input, $invalid_values);
                foreach ($result as $k => $r) {
                    $result[$k]['CertificateUrl'] = $gavahi_info['link'];
                    $result[$k]['CertificateNo'] = $gavahi_info['extra_info'][$k]['barcode'];
                }
            }
<<<<<<< HEAD
        }
        if ($input_alias == 'postalcode' && $output_alias == 'Address') {
            foreach ($result as $key => $res) {
                if (str_contains($res['statename'], trans('words.Tehran', [],'fa'))) {
                    $result[$key]['statename'] = trans('words.Tehran', [],'fa');
                }
            }
        }
=======
>>>>>>> dc6561e771088ad56da0d97435ec47ba653f6a95

            if ($input_alias == 'postalcode' && ($output_alias == 'Address' || $output_alias == 'AddressString')) {
                foreach ($result as $key => $res) {
                    if (str_contains($res['statename'], trans('words.Tehran', [], 'fa'))) {
                        $result[$key]['statename'] = trans('words.Tehran', [], 'fa');
                    }
                }
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
        return $name;

    }

    public static function requestPostCode($data, $user_id, $input)
    {
        $values[0] = [
            'ClientRowID' => $data['ClientRowID'],
            'TransactionID' => $data['TransactionID']
        ];
        $payment = new Payment();
        $tracking_code = $payment->getTrackingCode($data['TransactionID'], $values, $input);
        $action_areas = PopulationPoint::getActionAreas($data['localityCode']);
        $task_manager_params = self::createTaskManagerParams($data, $tracking_code, $user_id, $action_areas);

        $task_manager = new TaskManager();
        $status = $task_manager->createPostCodeTask($task_manager_params, $values, $input, $user_id);
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

    public static function requestPostCodes($data, $user_id, $input)
    {
        $tracking_code = null;
        $building = Building::getItem($data['BuildingID']);
        $data['localityCode'] = $building['population_point_id'];
        $post_unit = Redis::getPostUnit($building);
        $payment = new Payment();
        $invoice_id = $payment->createInvoice($post_unit);
        $quantity = count($data['BuildingUnits']);
        $payment_service_id = $payment->getServices();
        $invoice_line_id = $payment->insertInvoiceLine($invoice_id, $quantity, $payment_service_id);
        if (empty($invoice_line_id)) {
            throw new ServicesException(null, null, null,
                null, null, null, 12, trans('messages.custom.error.12'), null);
        }
        $payment_init = $payment->initPayment($invoice_id);
        if ($payment_init) {
            $tracking_code = $payment->getByUserId($invoice_line_id);
            if (empty($tracking_code)) {
                $res_msg = trans('messages.custom.error.transaction_not_found');
                throw new ServicesException(null,
                    null,
                    [],
                    null,
                    null,
                    null,
                    -34,
                    $res_msg,
                    'empty'
                );
            } else {
                $action_areas = PopulationPoint::getActionAreas($building['population_point_id']);
                $task_manager_params = self::createTaskManagerParams($data, $tracking_code, $user_id, $action_areas);
                $task_manager = new TaskManager();
                $status = $task_manager->createPostCodeTask($task_manager_params, null, null, $user_id);
                if ($status['message'] == 'successfully created') {
                    $msg = trans('messages.custom.success.ResMsg');
                    $res_data = [
                        "BuildingID" => $data['BuildingID'],
                        "TrackingCode" => $tracking_code
                    ];
                    return ServicesResponse::makeResponse2(0, $msg, $res_data);
                } else {
                    throw new ServicesException(null, null, null, null, null, null,
                        -2, trans('messages.custom.error.-12'), 'empty');
                }
            }
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
            "name" => $data['firstName'] ?? $data['FirstName'],
            "surname" => $data['lastName'] ?? $data['LastName'],
            "user_mobile" => $data['mobileNo'] ?? $data['MobileNo'],
            "detailed_address" => $data['address'] ?? $data['BuildingAddress'],
            "tracking_number" => $tracking_code,
        ];
        if (array_key_exists('RequestType', $data)) {
            $params["unique_features"]["request_type"] = $data['RequestType'];
        }
        if (array_key_exists('RequestType', $data)) {
            $params["unique_features"]["old_units_count"] = $data['OldUnitsCount'];
        }
        if (array_key_exists('OldPostCodes', $data)) {
            $params["unique_features"]["old_post_codes"] = $data['OldPostCodes'];
        }
        if (array_key_exists("prePhoneNo", $data) && array_key_exists("phoneNo", $data)) {
            $params["unique_features"]["phone_number"] = $data["prePhoneNo"] . $data["phoneNo"];
        }
        if (array_key_exists('lon', $data) && array_key_exists('lat', $data)) {
            $params["unique_features"]["detailed_address_geom"] = [
                $data['lon'],
                $data['lat']
            ];
        } elseif (array_key_exists('ParcelCoordinates', $data)) {
            $params["unique_features"]["detailed_address_geom"] = $data["ParcelCoordinates"];
        }
        if (array_key_exists('nearestPostCode', $data)) {
            $params["unique_features"]["nearest_postcode"] = $data['nearestPostCode'];
        }
        if (array_key_exists('floorNo', $data)
            && array_key_exists('sideFloor', $data)
            && array_key_exists('landUse', $data)) {
            $params["unique_features"]["units"][] =
                [
                    "floor" => $data['floorNo'],
                    "unit" => $data['sideFloor'],
                    "activityType" => $data['landUse']
                ];

        } else {
            foreach ($data['BuildingUnits'] as $unit) {
                $params["unique_features"]["units"][] =
                    [
                        "floor" => $unit['FloorNo'],
                        "unit" => $unit['SideFloor'],
                        "unique_id" => $unit['UniqueID'],
                        "unit_no" => $unit['UnitNo'],
                        "area" => $unit['Area']
                    ];

            }
        }
        if (array_key_exists('localityCode', $data)) {
            $params["unique_features"]["population_point_id"] = $data['localityCode'];
        }
        if (array_key_exists('email', $data)) {
            $params["unique_features"]["email"] = $data['email'];
        }
        if (array_key_exists('houseNo', $data)) {
            $params["unique_features"]["plate_no"] = $data['houseNo'];
        }
        if (array_key_exists('houseNo', $data)) {
            $params["unique_features"]["province"] = $data['unit'];
        }
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
            $res_data = 'empty';
        }
        return ServicesResponse::makeResponse2($code, $msg, $res_data);
    }

    public static function generateCertificateByTxn($data, $user_id, $input, $invalid_values, $output_alias, $scopes, $input_alias)
    {
        $values[0] = [
            "ClientRowID" => $data['ClientRowID'],
            "PostCode" => $data['PostCode']
        ];
        $payment = new Payment();
        $tracking_code = $payment->getTrackingCode($data['TransactionID'], $values, $input);

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

        $out_fields = self::createDatabaseFields($input, $output_alias);
        $action_areas = null;
        if ($scopes) {
            $action_areas = $scopes['action_areas'];
        }
        $polygon = Helper::getPolygon($values['geometry']['coordinates'][0]);

        $result = Post::postcodeByParcel($action_areas, $polygon, $out_fields, $values['geometry']);
        if (!empty($result)) {
            $data = [
                'ClientRowID' => $values['ClientRowID'],
                'geometry' => $values['geometry'],
                'Succ' => true,
                'Result' => [
                    'TraceID' => "",
                    "Postcodes" => [
                        $result
                    ],
                    'Errors' => null

                ]

            ];
            return ServicesResponse::makeResponse2(0, trans('messages.custom.success.ResMsg'), $data);
        } else {
            throw new ServicesException(
                null,
                null,
                null,
                null,
                null,
                null,
                404
                , trans('messages.custom.error.404'),
                'empty');
        }

    }


}
