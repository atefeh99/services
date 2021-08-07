<?php

namespace App\Http\Controllers;


use App\Models\Post;
use App\PostalPatrol;
use App\StaticMap;
use App\Helpers\Helpers;
use App\Http\Services\Gnafservices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function PHPUnit\Framework\isEmpty;
use App\Http\Controllers\ApiController;

class GnafController extends ApiController
{
//        if change key of aliases, must be change swagger route!
    public $can = [
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
            'AddressString'
        ],
        'tel' => [
            'Postcode',
            'Address',
            'AddressAndPostcode',
            'Workshop',
            'Position',
            'ValidateTelephone',
            'ActivityCode'
        ],

    ];
    public $aliases = [
        'location' => 'geom',
        'map' => 'static',
        'standard_address' => 'standard_address',
        'water' => 'water',
        'gaz' => 'gaz',
        'darai' => 'dara',
        'Telephone' => 'tel',
        'electricity' => 'elec',
        'Postcode' => 'postalcode',
        'postalcode' => 'Postcode',
        'modernization' => 'nosazi',
        'mobile' => 'mobile',
        'parcel' => 'parcel',

        'compact_address' => 'std_address',

        'unit' => 'Units',
        'conf_level' => 'conf_level',
        'blockno' => 'blockno',

        'building_type' => 'building_type',

        'building' => 'building',

        'activity_type' => 'activity',
        'activity_name' => 'name',
        'activity' => 'activity',


    ];
    public $outputcheck = [
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
        'EstimatedPosition' => 'EstimatedPosition'
    ];
    protected $casts = [
        'postalcode' => 'integer',
    ];
    public static $output_attrs = [];

    public function search($input, $output, Request $request)
    {


        $inputmaps = [
            'Telephone' => 'Telephones',
            'Postcode' => 'Postcodes'
        ];
        $inputm = [
            'Telephone' => 'TelephoneNo',
            'Postcode' => 'PostCode'
        ];

        $value = $request->all();
        $inputval= $value[$inputmaps[$input]];
        $count=count($inputval);
        $result=[];
        $inp=$input;
        $input = in_array($input, array_keys($this->aliases)) ? $this->aliases[$input] : $input;
        $output = in_array($output, array_keys($this->outputcheck)) ? $this->outputcheck[$output] : $output;
        if (!in_array($input, array_keys($this->can))) {
            return $this->respondError("$input is not valid",422,10002);
        }

        if ((in_array($input, array_keys($this->can)) && !in_array($output, $this->can[$input]))) {
            return $this->respondError("$output is not valid",422,10003);
        }

        $out_fileds = Gnafservices::createOutFields($output);
        for ($i=0;$i<$count;$i++){
            $inputvaleu=$inputval[$i][$inputm[$inp]];
        $response = Gnafservices::handlingField($input, $output, $inputvaleu, $out_fileds);
        $result[$i]=$response;
        }
        return $this->respondArrayResult($result);
       // return response()->json($response);
    }


}
