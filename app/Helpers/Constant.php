<?php


namespace App\Helpers;


class Constant
{
    const SUCCESS_RESPONSE_CODE = 0;
    const ERROR_RESPONSE_CODE = 12;

    const INPUTMAPS = [
        'Telephone' => 'Telephones',
        'Postcode' => 'Postcodes'
    ];
    const INPUTM = [
        'Telephone' => 'TelephoneNo',
        'Postcode' => 'PostCode'
    ];

    const ALIASES = [
//        'location' => 'geom',
//        'map' => 'static',
//        'standard_address' => 'standard_address',
//        'water' => 'water',
//        'gaz' => 'gaz',
//        'darai' => 'dara',
        'Telephone' => 'tels',
//        'electricity' => 'elec',
        'Postcode' => 'postalcode',
        'postalcode' => 'Postcode',
//        'modernization' => 'nosazi',
//        'mobile' => 'mobile',
//        'parcel' => 'parcel',
//
//        'compact_address' => 'std_address',
//
//        'unit' => 'Units',
//        'conf_level' => 'conf_level',
//        'blockno' => 'blockno',
//
//        'building_type' => 'building_type',
//
//        'building' => 'building',

//        'activity_type' => 'activity',
//        'activity_name' => 'name',
//        'activity' => 'activity',


    ];

    const OUTPUT_CHECK = [
        'PostcodeByTelephone' => 'Postcode',
        'AddressByTelephone' => 'Address',
        'AddressByPostcode' => 'Address',
        'AddressAndPostcodeByTelephone' => 'AddressAndPostcode',
        'WorkshopByTelephone' => 'Workshop',
        'PositionByTelephone' => 'Position',
        'ValidateTelephone' => 'ValidateTelephone',
        'ActivityCodeByTelephone' => 'ActivityCode',
        'AddressStringByPostcode' => 'AddressString',
        'TelephonesByPostcode' => 'Telephones',
        'AddressAndTelephonesByPostcode' => 'AddressAndTelephones',
        'PositionByPostcode' => 'Position',
        'ActivityCodeByPostcode' => 'ActivityCode',
        'WorkshopByPostcode' => 'Workshop',
        'BuildingUnitsByPostcode' => 'BuildingUnits',
        'GenerateCertificate' => 'GenerateCertificate',
        'ValidatePostCode' => 'ValidatePostCode',
        'AccuratePosition' => 'AccuratePosition',
        'EstimatedPosition' => 'EstimatedPosition',
        'ReqStatus' => 'ReqStatus',
        'GenerateCertificateByTxn'=> 'GenerateCertificateByTxn'
    ];

    const CAN = [
        'postalcode' => [
            'Telephones',
            'Postcode',
            'Address',
            'AddressAndTelephones',
            'Workshop',
            'Position',
            'ValidatePostCode',
            'ActivityCode',
            'BuildingUnits',
            'AddressString',
            'AccuratePosition',
            'EstimatedPosition',
            'GenerateCertificate',
            'ReqStatus'
        ],
        'tels' => [
            'Postcode',
            'Address',
            'AddressAndPostcode',
            'Workshop',
            'Position',
            'ValidateTelephone',
            'ActivityCode'
        ],

    ];

    const POSTCODE_PATTERN = '/^(?![02])(?!\d{1}[02])(?!\d{2}[02])(?!\d{3}[02])(?!\d{4}[025])(?!\d{5}0)(?!\d{6}0000)\d{10}$/m';


}
