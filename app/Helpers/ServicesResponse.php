<?php


namespace App\Helpers;

use App\Helpers\Constant;

class ServicesResponse
{

    public static function makeResponse($info, $inp, $input, $output, $values, $output_result, $invalid_inputs)
    {
        $data = array();
        //loop through postcodes or telephones
        foreach ($values as $PorT) {
            $area_code = '';
            $temp = $PorT[$inp];
            $client_row_id = $PorT['ClientRowID'];
            if (isset($PorT['AreaCode'])) {
                $area_code = $PorT['AreaCode'];
            }
            if (array_key_exists($temp, $info)) {
                //there is record but column is null
                if (($output == 'Telephones' && !$info[$temp]['tel'])
                    || ($output == 'BuildingUnits' && !$info[$temp]['unit'])
                    || ($output == 'Postcode' && !$info[$temp]['postalcode'])
                    || (($output == 'position' || $output == 'EstimatedPosition' || $output == 'AccuratePosition')
                        && (!$info[$temp]['st_x'] || !$info[$temp]['st_y']))
                ) {
                    $error_msg_part1 = trans('messages.custom.error.msg_part1');
                    $error_msg_part2 = '';
                    if ($output == 'Telephones') {
                        $error_msg_part2 = trans('messages.custom.error.telMsg');
                    } elseif ($output == 'Postcode') {
                        $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
                    } elseif ($output == 'position' || $output == 'EstimatedPosition' || $output == 'AccuratePosition') {
                        $error_msg_part2 = trans('messages.custom.error.positionMsg');
                    }
                    $data[$temp] = self::succFalse($client_row_id, $input, $area_code, $inp, $temp, $error_msg_part1, $error_msg_part2);
                } else {
                    $data[$temp] = self::succTrue($info[$temp], $client_row_id, $input, $area_code, $inp, $temp, $output, $output_result);
                }


//no data for the specific postcode or tel
            } else {
                $data[$temp] = self::recordNotFound($client_row_id, $temp, $area_code, $input, $inp, $invalid_inputs);
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

//no record in db
    public static function recordNotFound($client_row_id, $temp, $area_code, $input, $inp, $invalid_inputs)
    {
        if (in_array($temp, $invalid_inputs)) {
            $error_code = 1001;
            $error_msg_part2 = '';
            if ($input == 'postalcode') {
                $error_msg_part1 = trans('messages.custom.error.invalidPostcode');
            } else {
                $error_msg_part1 = '';
            }
        } else {
            $error_code = 9040;
            $error_msg_part1 = trans('messages.custom.error.msg_part1');
            if ($input == "tel") {
                $error_msg_part2 = trans('messages.custom.error.telMsg');
            } else {
                $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
            }
        }

        return self::succFalse($client_row_id, $input, $area_code, $inp, $temp, $error_msg_part1, $error_msg_part2, $error_code);
    }


    public static function getCodeAndMsg($data)
    {
        $array = collect($data)->unique('Succ')->values();
//        dd(count($array));
        if (!$array[0]['Succ'] && count($array) == 1) {
            $msg = trans('messages.custom.error.ResMsg');
            $code = Constant::ERROR_RESPONSE_CODE;
        } else {
            $msg = trans('messages.custom.success.ResMsg');
            $code = Constant::SUCCESS_RESPONSE_CODE;
        }

        return ['code' => $code, 'msg' => $msg];
    }

    public static function succFalse($client_row_id, $input, $area_code, $inp, $temp, $error_msg_part1, $error_msg_part2, $error_code = 9040)
    {
        $record = [
            'ClientRowID' => $client_row_id,
        ];
        if ($input == "tel") {

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


    public
    static function succTrue($info, $client_row_id, $input, $area_code, $inp, $temp, $output, $output_result)
    {
        $record = [
            'ClientRowID' => $client_row_id,
        ];
        if ($input == "tel") {
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
        else {
            foreach ($info as $key => $value) {
                //change the keys when we have result
                $new_key = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
                $attribute = $value;
                if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                    $attribute = 'true';
                }
                unset($info[$key]);
                $info[$new_key] = $attribute;

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
