<?php


namespace App\Helpers;

use App\Helpers\Constant;

class ServicesResponse
{

    public static function makeResponse($input, $info, $input_alias, $output_alias, $values, $output_result, $invalid_values)
    {
        $inp = Constant::INPUTM[$input];
        $data = array();
        //loop through postcodes or telephones
        foreach ($values as $PorT) {
            $area_code = '';
            $temp = $PorT[$inp];
            $client_row_id = $PorT['ClientRowID'];
            if ($input == 'Telephone') {
                $area_code = isset($info[$temp]) ? $info[$temp]['areacode'] : $PorT['AreaCode'];
            }
            if (array_key_exists($temp, $info)) {
                //there is record but column is null
                if (($output_alias == 'Telephones' && !$info[$temp]['tels'])
                    || ($output_alias == 'BuildingUnits' && !$info[$temp]['unit'])
                    || ($output_alias == 'Postcode' && !$info[$temp]['postalcode'])
                    || (($output_alias == 'Position' || $output_alias == 'EstimatedPosition' || $output_alias == 'AccuratePosition')
                        && (!$info[$temp]['st_x'] || !$info[$temp]['st_y'])
                        || ($output_alias == 'ActivityCode' && !$info[$temp]['activity_type'])
                        || ($output_alias == 'Postcode' && !$info[$temp]['postalcode'])
                    )
                ) {
                    $error_msg_part1 = trans('messages.custom.error.msg_part1');
                    $error_msg_part2 = '';
                    if ($output_alias == 'Telephones') {
                        $error_msg_part2 = trans('messages.custom.error.telMsg1');
                    } elseif ($output_alias == 'Postcode') {
                        $error_msg_part2 = trans('messages.custom.error.postcodeMsg1');
                    } elseif ($output_alias == 'Position' || $output_alias == 'EstimatedPosition' || $output_alias == 'AccuratePosition') {
                        $error_msg_part2 = trans('messages.custom.error.positionMsg');
                    } elseif ($output_alias == 'BuildingUnits') {
                        $error_msg_part2 = trans('messages.custom.error.unitMsg');
                    } elseif ($output_alias == 'ActivityCode') {
                        $error_msg_part2 = trans('messages.custom.error.activitycodeMsg');
                    }
                    $data[$temp] = self::succFalse($input, $area_code, $inp, $temp, $invalid_values, 9040, $error_msg_part1, $error_msg_part2, $client_row_id);
                } else {
                    $data[$temp] = self::succTrue($info[$temp], $client_row_id, $input, $area_code, $inp, $temp, $output_alias, $output_result);
                }
//no data for the specific postcode or tel
            } else {
                $data[$temp] = self::succFalse($input, $area_code, $inp, $temp, $invalid_values, null, null, null, $client_row_id);
            }

        }
        //todo get code msg data
        $code_and_message = self::getCodeAndMsg($data);
        return [
            "ResCode" => $code_and_message['code'],
            "ResMsg" => $code_and_message['msg'],
            "Data" => array_values($data)
        ];

    }

    public static function makeResponse2($code, $message, $data)
    {
        return [
            "ResCode" => $code,
            "ResMsg" => $message,
            "Data" => $data
        ];
    }

    public static function getCodeAndMsg($data)
    {
        $array = collect($data)->unique('Succ')->values();
        if (!$array[0]['Succ'] && count($array) == 1) {
            $msg = trans('messages.custom.error.ResMsg');
            $code = Constant::ERROR_RESPONSE_CODE;
        } else {
            $msg = trans('messages.custom.success.ResMsg');
            $code = Constant::SUCCESS_RESPONSE_CODE;
        }

        return ['code' => $code, 'msg' => $msg];
    }

    public static function succFalse($input, $area_code, $inp, $temp,
                                     $invalid_values = null,
                                     $error_code = null,
                                     $error_msg_part1 = null,
                                     $error_msg_part2 = null,
                                     $client_row_id = null)
    {
        $record = [];

        if (isset($invalid_values) && in_array($temp, $invalid_values)) {

            if ($input == 'Postcode') {
                $error_code = 1001;
                $error_msg_part1 = trans('messages.custom.error.invalidPostcode');
                $error_msg_part2 = '';

            } elseif ($input == 'Telephone') {
                if ($area_code <= 0) {
                    $error_code = 1201;
                    $error_msg_part1 = trans('messages.custom.error.1201');
                    $error_msg_part2 = '';
                } elseif ($temp <= 0) {
                    $error_code = 1202;
                    $error_msg_part1 = trans('messages.custom.error.1202');
                    $error_msg_part2 = '';
                }
            }
        } elseif (empty($error_code)) {
            $error_code = 9040;
            if ($input == "Telephone") {
                $error_msg_part2 = trans('messages.custom.error.telMsg');
            } else {
                $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
            }

        }
        if (!empty($client_row_id)) {
            $record = [
                'ClientRowID' => $client_row_id,
            ];
        }
        if ($input == "Telephone") {

            $record += [
                'AreaCode' => $area_code];
        }
        $record += [
            $inp => $temp,
            'Succ' => false,
            'Result' => null,
            'Errors' => [
                'ErrorCode' => $error_code,
                'ErrorMessage' => $error_msg_part1 . $error_msg_part2
            ]
        ];
        return $record;
    }


    public static function succTrue($info, $client_row_id, $input, $area_code, $inp, $temp, $output, $output_result)
    {
        $record = [
            'ClientRowID' => $client_row_id,
        ];
        if ($input == "Telephone") {
            $record += [
                'AreaCode' => $area_code];
        }
        $record += [
            $inp => $temp,
            'Succ' => true,
        ];

        if ($output == "AddressString") {
            $record['Result'] = [
                'Value' => self::makeAddressString($info),
                "PostCode" => $temp,
            ];
        } //loop through postalcode or telephones
        elseif (($output == "Telephones" || $output == 'AddressAndTelephones') && $info['tels']) {

            foreach ($info['tels'] as $key => $tel) {
                $telephones[$key]["AreaCode"] = $info['areacode'];
                $telephones[$key]["SubscriberNumber"] = $tel;
            }
            $record['Result']['TelephoneNo'] = $telephones;
        } else {
            foreach ($info as $key => $value) {
                //change the keys when we have result
                $new_key = array_key_exists($key, $output_result) ? $output_result[$key] : null;
                $attribute = $value;
                if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                    $attribute = 'true';
                }
                unset($info[$key]);
                if ($new_key) {
                    $info[$new_key] = $attribute;
                }

            }
            $record['Result'] = $info;
        }
        $record['Result'] += [
            'TraceID' => "",
            'ErrorCode' => 0,
            'ErrorMessage' => null
        ];
        $record['Errors'] = null;
        return $record;

    }

    public static function makeAddressString($v)
    {

        $result = "";
        $state_not_null = !empty($v['statename']);
        $town_not_null = !empty($v['townname']);
        $zone_not_null = !empty($v['zonename']);
        $location_type_not_null = !empty($v['locationtype']);
        $location_name_not_null = !empty($v['locationname']);
        $parish_not_null = !empty($v['parish']);
        $mainavenue_not_null = !empty($v['mainavenue']);
        $preaventype_not_null = !empty($v['preaventypename']);
        $preaven_not_null = !empty($v['preaven']);
        $avenuetype_not_null = !empty($v['avenuetypename']);
        $avenue_not_null = !empty($v['avenue']);
        $plate_not_null = isset($v['plate_no']);
        $building_not_null = !empty($v['building_name']);
        $building_type_not_null = !empty($v['building_type']);
        $entrance_not_null = !empty($v['entrance']);
        $floor_not_null = isset($v['floorno']);
        $unit_not_null = !empty($v['unit']);

        //state
        if ($state_not_null && $v['statename']) {
            $result .= 'استان ';
            $result .= $v['statename'];
            if (
                ($town_not_null && $v['townname'])
                || ($zone_not_null && $v['zonename'])
                || (($location_name_not_null && $v['locationname'])
                    || ($location_type_not_null && $v['locationtype']))
                || ($parish_not_null && $v['parish'])
                || ($mainavenue_not_null && $v['mainavenue'])
                || (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                || ($plate_not_null)
                || ($entrance_not_null && $v['entrance'])
                || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                || ($floor_not_null)
                || ($unit_not_null && $v['unit'])

            ) $result .= '، ';
        }

        //town
        if ($town_not_null && $v['townname']) {
            $result .= 'شهرستان ';
            $result .= $v['townname'];
            if (
                ($zone_not_null && $v['zonename'])
                || (($location_name_not_null && $v['locationname'])
                    || ($location_type_not_null && $v['locationtype']))
                || ($parish_not_null && $v['parish'])
                || ($mainavenue_not_null && $v['mainavenue'])
                || (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                || ($plate_not_null)
                || ($entrance_not_null && $v['entrance'])
                || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                || ($floor_not_null)
                || ($unit_not_null && $v['unit'])

            ) $result .= '، ';
        }

        //zone
        if ($zone_not_null && $v['zonename']) {
            $result .= 'بخش ';
            $result .= $v['zonename'];
            if (
                (($location_name_not_null && $v['locationname'])
                    || ($location_type_not_null && $v['locationtype']))
                || ($parish_not_null && $v['parish'])
                || ($mainavenue_not_null && $v['mainavenue'])
                || (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                || ($plate_not_null)
                || ($entrance_not_null && $v['entrance'])
                || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                || ($floor_not_null)
                || ($unit_not_null && $v['unit'])

            ) $result .= '، ';
        }

        //location
        if ($location_type_not_null
            && $location_name_not_null) {

            if ($v['locationtype'] || $v['locationname']) {
                if ($v['locationtype'] == 'روستا') {
                    $result .= 'روستای ';
                    $result .= $v['locationname'];
                } else {
                    $result .= $v['locationtype'] . ' ' . $v['locationname'];
                }
                if (
                    ($parish_not_null && $v['parish'])
                    || ($mainavenue_not_null && $v['mainavenue'])
                    || (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                    || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                    || ($plate_not_null)
                    || ($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])

                ) $result .= '، ';
            }
        }

        //parish
        if ($parish_not_null) {
            if ($v['parish']) {
                $result .= $v['parish'];
                if (
                    ($mainavenue_not_null && $v['mainavenue'])
                    || (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                    || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                    || ($plate_not_null)
                    || ($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])

                ) $result .= '، ';
            }
        }

        //mainavenue
        if ($mainavenue_not_null) {
            if ($v['mainavenue']) {
                $result .= $v['mainavenue'];
                if (
                    (($preaven_not_null && $v['preaven']) || ($preaventype_not_null && $v['preaventypename']))
                    || (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                    || ($plate_not_null)
                    || ($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])

                ) $result .= '، ';
            }
        }

        //preavenue
        if (
            $preaventype_not_null
            && $preaven_not_null

        ) {
            if ($v['preaventypename'] ||
                $v['preaven']) {
                $result .= $v['preaven'];

                if (
                    (($avenue_not_null && $v['avenue']) || ($avenuetype_not_null && $v['avenuetypename']))
                    || ($plate_not_null)
                    || ($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])

                ) $result .= '، ';
            }


        }

//        avenue
        if ($avenuetype_not_null
            && $avenue_not_null) {
            if ($v['avenuetypename'] ||
                $v['avenue']) {
                $result .= $v['avenue'];

                if (($plate_not_null)
                    || ($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])
                ) $result .= '، ';
            }
        }

//        plateno
        if ($plate_not_null) {
            $result .= 'پلاک ';
            $result .= abs($v['plate_no']);
            if ($v['plate_no'] < 0) {
                if (($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])
                ) {
                    $result .= '-';
                    $result .= '، ';
                } else {
                    $result = '-' . $result;
                }

            } else {
                if (($entrance_not_null && $v['entrance'])
                    || (($building_not_null && $v['building_name']) || ($building_type_not_null && $v['building_type']))
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])
                ) {
                    $result .= '، ';
                }
            }
        }

        //building_name
        if ($building_not_null && $building_type_not_null) {
            if ($v['building_name'] && $v['building_type']) {
                $result .= $v['building_type'] . ' ';
                $result .= $v['building_name'];
                if (($entrance_not_null && $v['entrance'])
                    || ($floor_not_null)
                    || ($unit_not_null && $v['unit'])
                ) $result .= '، ';
            }

        }

        //entrance
        if ($entrance_not_null) {
            if ($v['entrance']) {
                $result .= $v['entrance'];
                if (($floor_not_null)
                    || ($unit_not_null && $v['unit'])
                ) $result .= '، ';
            }

        }

//        floor
        if ($floor_not_null) {
            $result .= 'طبقه ';
            $result .= ((int)$v['floorno'] == 0) ?
                'همکف' : abs($v['floorno']);
            if ((int)$v['floorno'] < 0) {
                if ($unit_not_null && $v['unit']) {
                    $result .= '-';
                    $result .= '، ';
                } else {
                    $result = '-' . $result;
                }
            } else {
                if ($unit_not_null && $v['unit']) {
                    $result .= '، ';
                }
            }
        }

//        unit
        if ($unit_not_null) {
            if ($v['unit']) {
                $result .= 'واحد ';
                $result .= $v['unit'];
            }
        }

        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $num = range(0, 9);
        $result = str_replace($num, $persian, $result);

        return $result;
    }


}
