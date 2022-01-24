<?php


namespace App\Helpers;

use App\Helpers\Constant;

class ServicesResponse
{

    public static function makeResponse($input, $info, $input_alias, $output_alias, $values, $output_result, $invalid_values)
    {
//        dd($input_alias);
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
//                    || ($output_alias == 'AddressAndTelephones' && !$info[$temp]['tels'] && !$info[$temp]['statename'] && !$info[$temp]['townname'] && !$info[$temp]['zonename'])
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
                        $error_msg_part2 = trans('messages.custom.error.telMsg');
                    } elseif ($output_alias == 'Postcode') {
                        $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
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
            $error_msg_part1 = trans('messages.custom.error.msg_part1');
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
                $result .= $v['preaven'];
                $result .= '، ';

            }
            if ($v['avenuetypename'] ||
                $v['avenue']) {
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
        }

        return $result;
    }


}
