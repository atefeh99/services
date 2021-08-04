<?php

namespace App\Http\Controllers;


use App\Models\Post;
use App\PostalPatrol;
use App\StaticMap;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function PHPUnit\Framework\isEmpty;

class GnafController extends Controller
{
//        if change key of aliases, must be change swagger route!
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
//        fields that is set as array in db!
    public $farsi = [
        'statename' => 'استان',
        'townname' => '',
        'zonename' => 'بخش',
//        'villagename' => 'village',
//        'locationtype' => 'localitiytype',
//        'locationname' => 'localityname',
        'parish' => 'محله',
        'preaven' => 'خیابان',
        'avenue' => 'خیابان',
        'pelak' => 'پلاک',
        'floorno' => 'طبقه',
//        'building_name' => 'building_name'
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
    public $composite_result = [
        'Position' => [
            'st_x' => 'Latitude',
            'st_y' => 'Longitude'
        ]
        ,
        'Telephones' => [
            'tel' => 'SubscriberNumber'
        ],
        'Postcode' => [
            'postalcode' => 'Value'
        ],
        'Address' => [
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'pelak' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name'

        ],
        'AddressAndTelephones' => [
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'pelak' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name',
            'tel' => 'TelephoneNo'

        ],
        'AddressAndPostcode' => [
            'postalcode' => 'Postcode',
            'statename' => 'province',
            'townname' => 'township',
            'zonename' => 'zone',
            'villagename' => 'village',
            'locationtype' => 'localitiytype',
            'locationname' => 'localityname',
            'parish' => 'sublocality',
            'preaven' => 'street2',
            'avenue' => 'street',
            'pelak' => 'Housenumber',
            'floorno' => 'floor',
            'building_name' => 'building_name',
        ],
        'Workshop' => [
            'activity_type' => 'Activity',
            'activity_name' => 'Name'
        ],
        'ValidateTelephone' => [
            'tel' => 'Value'
        ],
        'ValidatePostCode' => [
            'postalcode' => 'Value'
        ],
        'BuildingUnits' => [
            'unit' => 'Units'
        ],
        'AddressString' => [
            'address' => 'Value'
        ]

    ];
    public $composite_output = [
        'Position' => ['ST_X(geom),ST_Y(geom)']
        ,
        'Telephones' => [
            'tel'
        ],
        'Postcode' => [
            'postalcode'
        ],
        'Address' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'pelak',
            'floorno',
            'building_name'
        ],
        'AddressString' => [
            'statename',
            'townname',
            'zonename',
//            'villagename',
//            'locationtype',
//            'locationname',
            'parish',
            'preaven',
            'avenue',
            'pelak',
            'floorno',
//            'building_name'
        ],
        'AddressAndTelephones' => [
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'pelak',
            'floorno',
            'building_name',
            'tel'
        ],
        'AddressAndPostcode' => [
            'postalcode',
            'statename',
            'townname',
            'zonename',
            'villagename',
            'locationtype',
            'locationname',
            'parish',
            'preaven',
            'avenue',
            'pelak',
            'floorno',
            'building_name',

        ],
        'Workshop' => [
            'activity_name',
            'activity_type'
        ],
        'ValidateTelephone' => [
            'tel'
        ],
        'ValidatePostCode' => [
            'postalcode'
        ],
        'BuildingUnits' => [
            'unit'
        ]
    ];
    protected $casts = [
        'postalcode' => 'integer',
    ];
    public $output_attrs = [];

    public function search($input, $output, Request $request)
    {

        $inputmaps = [
            'Telephone' => 'Telephones',
            'Postcode' => 'PostCodes'
        ];
        $value = $request->all();
        $inputvaleu = $value[$inputmaps[$input]];

        $input = in_array($input, array_keys($this->aliases)) ? $this->aliases[$input] : $input;
        $output = in_array($output, array_keys($this->outputcheck)) ? $this->outputcheck[$output] : $output;
        if (!in_array($input, array_keys($this->can))) {
            return response()->json([
                'status' => 422,
                'message' => "$input is not valid",
                'fields' => [$input => 'is required'],
                'code' => 10002
            ], 422);
        }

        if ((in_array($input, array_keys($this->can)) && !in_array($output, $this->can[$input]))) {
            return response()->json([
                'status' => 422,
                'message' => "$output is not valid",
                'fields' => [$output => 'is required'],
                'code' => 10003
            ], 422);
        }
        $this->createOutFields($output);
        $out_fileds = $this->output_attrs;
        $response = $this->handlingField($input, $output, $inputvaleu, $out_fileds);
        return response()->json($response);
    }

    public function handlingField($input, $output, $value, $out_fields)
    {

        $output_result = $this->createresultFields($output);
        if ($input == "tel") {
            $result = Post::searchinarray($input, $value, $out_fields);
        } else {
            $result = Post::search($input, $value, $out_fields);
        }

        if ($output == "AddressString") {
            $r = "";
            foreach ($result as $key => $value) {
                $r .= array_key_exists($key, $this->farsi) ? $this->farsi[$key] : $key;
                $r .= ' '.$value.' ,';
            }
            unset($result);
            $result['address'] = $r;
        }

        foreach ($result as $key => $value) {

            $key1 = array_key_exists($key, $output_result) ? $output_result[$key] : $key;
            if ($output == "ValidatePostCode" || $output == "ValidateTelephone") {
                    $temp['Value'] = "true";
            }
             else {
                $temp[$key1] = $result[$key];
            }
        }

        $temp['ErrorCode'] = 0;
        $temp['ErrorMessage'] = null;
        $temp['TraceID'] = "";


        return $temp;
    }


    private function createOutFields($name)
    {
        if (in_array($name, array_keys($this->composite_output))) {
            return array_map(function ($item) use ($name) {
                return $this->createOutFields($item);
            }, $this->composite_output[$name]);
        } else {
            $this->output_attrs[] = $name;
            return $name;
        }
    }

    private function createresultFields($name)
    {
        $name = in_array($name, array_keys($this->composite_result)) ? $this->composite_result[$name] : $name;
        return $name;

    }
}
