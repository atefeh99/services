<?php
//
//
//namespace App\Helpers;
//
//
//class ServicesResponse
//{
//    public $res_code;
//    public $res_msg;
//
//    public function __construct($code, $msg)
//    {
//        $this->res_code = self::getCode();
//        $this->res_msg = self::getMsg();
//        $this->data = self::makeData();
//
//    }
//
//
//    public function getCode()
//    {
//
//    }
//    public function getMsg()
//    {
//
//    }
//    public function makeValidData()
//    {
//        $data = array();
//
//        if (isset($info)) {
//            //loop through postcodes or telephones
//            foreach ($values as $PorT) {
////                dd($PorT);
//                //check if the postcode has data or not
//                $temp = $PorT[$inp];
//                $client_row_id = $PorT['ClientRowID'];
//                if (isset($PorT['AreaCode'])) {
//                    $area_code = $PorT['AreaCode'];
//                }
//                if (array_key_exists($temp, $info)) {
//                    $data[$temp] = [
//                        'ClientRowID' => $client_row_id,
//                    ];
//                    if ($input == "tel") {
//                        $data[$temp] += [
//                            'AreaCode' => $area_code];
//                    }
//                    $data[$temp] += [
//                        $inp => $temp,
//                        'Succ' => true,
//                    ];
//                    if ($output == "AddressString") {
//                        $result[$temp]['address'] = self::makeAddressString($info[$temp]);
////                        dd($result[$temp]);
//                        $data[$temp]['Result'] = [
//                            'Value' => $result[$temp]['address'],
//                            "PostCode" => $temp,
//                            'TraceID' => "",
//                            'ErrorCode' => 0,
//                            'ErrorMessage' => null
//                        ];
//
//                    } else {
//                        //loop through postalcode or telephones
//                        foreach ($result as $k => $v) {
//                            //loop through attributes
//                            $error_msg_part1 = trans('messages.custom.error.msg_part1');
//                            if ($output == 'Telephones' && !$v['tel']) {
//                                $error_msg_part2 = trans('messages.custom.error.telMsg');
//                                $data[$temp]['Succ'] = false;
//                                $data[$temp] += [
//                                    'Result' => null,
//                                    'Errors' => [
//                                        'ErrorCode' => 9040,
//                                        'ErrorMessage' => $error_msg_part1 . $error_msg_part2
//                                    ]
//                                ];
//                            } elseif ($output == 'BuildingUnits' && !$v['unit']) {
//                                $data[$temp]['Succ'] = false;
//                                $data[$temp] += [
//                                    'Result' => null,
//                                    'Errors' => [
//                                        'ErrorCode' => 9040,
//                                        'ErrorMessage' => $error_msg_part1
//                                    ]
//                                ];
//                            }  elseif ($output == 'Postcode' && !$v['postalcode']) {
//                                $data[$temp]['Succ'] = false;
//                                $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
//
//                                $data[$temp] += [
//                                    'Result' => null,
//                                    'Errors' => [
//                                        'ErrorCode' => 9040,
//                                        'ErrorMessage' => $error_msg_part1 . $error_msg_part2,
//                                    ]
//                                ];
//                            } elseif (( $output == 'position' || $output == 'EstimatedPosition' || $output == 'AccuratePosition')
//                                && (!$v['st_x'] || !$v['st_y'])) {
//                                $data[$temp]['Succ'] = false;
//                                $error_msg_part2 = trans('messages.custom.error.positionMsg');
//
//                                $data[$temp] += [
//                                    'Result' => null,
//                                    'Errors' => [
//                                        'ErrorCode' => 9040,
//                                        'ErrorMessage' => $error_msg_part1 . $error_msg_part2,
//                                    ]
//                                ];
//                            } else {
//                                foreach ($v as $key => $value) {
//                                    //change the keys when we have result
//                                    $key1 = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
//                                    $attribute = $value;
////                                dd($attribute);
//                                    if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
//                                        $attribute = 'true';
//                                    }
//                                    unset($result[$k][$key]);
//                                    $result[$k][$key1] = $attribute;
//
//                                }
//                                $data[$temp]['Result'] = $result[$k];
//                                $data[$temp]['Result'] += [
//                                    'TraceID' => "",
//                                    'ErrorCode' => 0,
//                                    'ErrorMessage' => null
//                                ];
//                                $data[$temp]['Errors'] = null;
//                            }
//
//                        }
//                    }
//
////no data for the specific postcode or tel
//                } else {
//                    $error_code = 9040;
//                    $error_msg_part1 = trans('messages.custom.error.msg_part1');
//                    $error_msg_part2 = trans('messages.custom.error.postcodeMsg');
//                    $data[$temp] = [
//                        'ClientRowID' => $client_row_id,
//                    ];
//                    if ($input == "tel") {
//                        $error_msg_part2 = trans('messages.custom.error.telMsg');
//
//                        $data[$temp] += [
//                            'AreaCode' => $area_code];
//                    }
//                    $data[$temp] += [
//                        $inp => $PorT[$inp],
//                        'Succ' => false,
//                        'Result' => null,
//                        'Errors' => [
//                            'ErrorCode' => $error_code,
//                            'ErrorMessage' => $error_msg_part1 . $error_msg_part2
//                        ]
//                    ];
//
//                }
//            }
//            return [
//                "ResCode" => 0,
//                "ResMsg" => trans('messages.custom.success.ResMsg'),
//                "Data" => array_values($data)
//            ];
//            //if no data available for all postcodes
//        } else {
//
//        }
//
//    }
//    public function makeInvalidData()
//    {
//        self::makeInvalidData();
//
//        foreach ($values as $PorT) {
//
//            $temp = $PorT[$inp];
//            $client_row_id = $PorT['ClientRowID'];
//            $data[$temp] = [
//                'ClientRowID' => $client_row_id,
//            ];
//            if (isset($PorT['AreaCode'])) {
//                $area_code = $PorT['AreaCode'];
//                $data[$temp] += [
//                    'AreaCode' => $area_code];
//            }
//            $data[$temp] += [
//                $inp => $temp,
//                'Succ' => false,
//                'Result' => null,
//                'Errors' => [
//                    'ErrorCode' => "",
//                    'ErrorMessage' => ""
//                ]
//            ];
//        }
//        return [
//            "ResCode" => 12,
//            "ResMsg" => trans('messages.custom.error.ResMsg'),
//            "Data" => array_values($data)
//        ];
//
//    }
//}
