<?php

namespace App\Http\Controllers;

use App\Exceptions\ServicesException;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;

trait RulesTrait
{

    public static function messages()
    {
        return [
            'required' => trans('messages.custom.error.-7'),
        ];
    }

    public static function rules()
    {
        return [
            RouteCRUDController::class => [
                'createItem' => [
                    'uri' => 'required|string',
                    'description' => 'required|string',
                    'fa_name' => 'required|string',
                    'document_link' => 'required|string'
                ],
                'readItem' => [
                    'id' => 'numeric|required',
                ],
                'updateField' => [
                    'id' => 'required|numeric',
                    'uri' => 'string',
                    'description' => 'string',
                    'fa_name' => 'string',
                    'document_link' => 'string',
                ],
                'deleteRecord' => [
                    'id' => 'integer',
                ],

            ],
            GnafController::class => [
                'auth' => [
                    'userpass' => [
                        'username' => 'required',
                        'password' => 'required',
                        'grant_type'=> 'required'
                    ],
                ],
                'search' => [
                    'postcode' => [
                        'ClientBatchID' => 'numeric|required',
                        'Postcodes' => 'required|array',
                        'Postcodes.*' => 'required|array',
                        'Postcodes.*.ClientRowID' => 'required|numeric',
                        'Postcodes.*.PostCode' => 'required',
                        'Signature' => 'string'
                    ],
                    'telephone' => [
                        'ClientBatchID' => 'numeric|required',
                        'Telephones' => 'required|array',
                        'Telephones.*' => 'required|array',
                        'Telephones.*.ClientRowID' => 'required|numeric',
                        'Telephones.*.TelephoneNo' => 'required',
                        'Telephones.*.AreaCode' => 'required',
                        'Signature' => 'string'
                    ],
                ],
                'reqStatus' => [
                    'postcode' => [
                        'NationalCode' => 'required|numeric',
                        'PostalCode' => 'required|numeric'
                    ]
                ],
                'requestPostCode' => [
                    'postcode' => [
                        'TransactionID' => 'integer|required',
                        'ClientRowID' => 'integer|required',
                        'localityCode' => 'integer|required',
                        'unit' => 'integer',
                        'landUse' => 'integer|required',
                        'firstName' => 'string|required',
                        'lastName' => 'string|required',
                        'mobileNo' => 'numeric|required',
                        'prePhoneNo' => 'numeric|required',
                        'phoneNo' => 'numeric|required',
                        'email' => 'string|required',
                        'nearestPostCode' => 'string|required',
                        'address' => 'string|required',
                        'houseNo' => 'string|required',
                        'floorNo' => 'integer|required',
                        'sideFloor' => 'string|required',
                        'lat' => 'numeric|required',
                        'lon' => 'numeric|required',
                        'Signature' => 'string'
                    ]
                ],
                'trackRequest' => [
                    'postcode' => [
                        'ClientRowID' => 'integer|required',
                        'FollowUpCode' => 'string|required',
                        'signature' => 'string'
                    ]
                ],
                'generateCertificateByTxn' => [
                    'postcode' => [
                        'TransactionID' => 'integer|required',
                        'ClientRowID' => 'integer|required',
                        'PostCode' => 'string|required',
                        'signature' => 'string'
                    ]
                ],
                'addressByCertificateNo' => [
                    'CertificateNo' => [
                        'ClientRowID' => 'integer|required',
                        'CertificateNo' => 'numeric|required',
                        'signature' => 'string'
                    ]
                ],
                'postcodeByParcel' => [
                    'parcel' => [
                        'ClientBatchID' => 'numeric|required',
                        'Parcels' => 'required|array',
                        'Parcels.*' => 'required|array',
                        'Parcels.*.ClientRowID' => 'required|numeric',
                        'Parcels.*.Latitude' => 'required',
                        'Parcels.*.Longitude' => 'required',
                        'Signature' => 'string'
                    ]
                ]
            ]
        ];
    }

    public static function checkRules($data, $function, $code = null, $input = null)
    {
        $controller = __CLASS__;
        if (strpos($controller, 'GnafController') == true) {
            $category = '';
            if (array_key_exists('Postcodes', $data) || array_key_exists('PostalCode', $data) || $input == 'Postcode') {
                $category = 'postcode';

            } elseif (array_key_exists('Telephones', $data)) {
                $category = 'telephone';
            } elseif (array_key_exists('CertificateNo', $data)) {
                $category = 'CertificateNo';
            } elseif (array_key_exists('Parcels', $data)) {
                $category = 'parcel';
            } elseif ($function == 'auth') {
                $category = 'userpass';
            }
            if ((array_key_exists('Postcodes', $data) && count($data['Postcodes']) > 10) ||
                (array_key_exists('Telephones', $data) && count($data['Telephones']) > 10) ||
                (array_key_exists('Parcels', $data) && count($data['Parcels']) > 10)
            ) {
                $msg = trans('messages.custom.error.-1');
                throw new ServicesException(null,
                    null,
                    null,
                    null,
                    null, null, -1, $msg, 'empty');
            }
            if (is_object($data)) {
                $validation = Validator::make(
                    $data->all(),
                    self::rules()[$controller][$function][$category],
                    self::messages()
                );
            } else {
                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function][$category],
                    self::messages()

                );
            }
        } else {
            if (is_object($data)) {
                $validation = Validator::make(
                    $data->all(),
                    self::rules()[$controller][$function]
                );
            } else {

                $validation = Validator::make(
                    $data,
                    self::rules()[$controller][$function]
                );
            }
        }

        if ($validation->fails()) {
            if (strpos($controller, 'GnafController') == true) {
                $errors = $validation->errors()->toArray();
                foreach ($errors as $error) {
                    foreach ($error as $item) {
                        if ($item == trans('messages.custom.error.-7')) {
                            throw new ServicesException(
                                null,
                                null,
                                [],
                                null,
                                null,
                                null,
                                -7,
                                trans('messages.custom.error.-7'),
                                'empty'
                            );
                        }
                    }
                }

                throw new ServicesException(
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    -6,
                    trans('messages.custom.error.-6'),
                    'empty'
                );
            } elseif (strpos($controller, 'RouteCRUDController') == true) {
                throw new RequestRulesException($validation->errors()->getMessages(), $code);
            }
        }
        return $validation->validated();
    }
}
