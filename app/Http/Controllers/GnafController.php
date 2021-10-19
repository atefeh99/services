<?php

namespace App\Http\Controllers;


use App\Helpers\Constant;
use App\Models\Post;
use App\Http\Services\Gnafservices;
use Illuminate\Http\Request;
use App\Http\Controllers\RulesTrait;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Controllers\ApiController;

class GnafController extends ApiController
{
    use RulesTrait;

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
            'AddressString',
            'AccuratePosition',
            'EstimatedPosition'
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
    const ALIASES = [
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

        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            $input,
        '');
        $inputval = $data[Constant::INPUTMAPS[$input]];

        $inputval = is_string($inputval) ? [$inputval] : $inputval;
//        $count = is_string($inputval) ? 1 : count($inputval);
        $inp = $input;
        $invalid_inputs = self::findInvalids($inputval, Constant::INPUTM[$inp]);

        $input = in_array($input, array_keys(self::ALIASES)) ? self::ALIASES[$input] : $input;
        $output = in_array($output, array_keys($this->outputcheck)) ? $this->outputcheck[$output] : $output;
        if (!in_array($input, array_keys($this->can))) {
            return $this->respondError("$input is not valid", 422, 10002);
        }
        if ((in_array($input, array_keys($this->can)) && !in_array($output, $this->can[$input]))) {

            return $this->respondError("$output is not valid", 422, 10003);
        }
        $out_fileds = Gnafservices::createDatabaseFields($inp, $output);

//        dd($inputm[$inp],$input, $output, $inputval, $out_fileds);
        $response = Gnafservices::serach(Constant::INPUTM[$inp], $input, $output, $inputval, $out_fileds, $invalid_inputs);

        return $this->respondArrayResult($response);
    }

    public function findInvalids($inputval, $inp)
    {
        $data = collect($inputval)->pluck($inp)->all();
        if ($inp == 'PostCode') {
            $invalids = [];
            foreach ($data as $datum) {
                $flag = preg_match('/^(?![02])(?!\d{1}[02])(?!\d{2}[02])(?!\d{3}[02])(?!\d{4}[025])(?!\d{5}0)(?!\d{6}0000)\d{10}$/m'
                    , $datum
                );
                if ($flag == 0) {
                    $invalids[] = $datum;
                }
            }
            return $invalids;
        } else {
            //todo tel regex
            return [];
        }
    }


}
