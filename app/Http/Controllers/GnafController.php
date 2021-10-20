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


    protected $casts = [
        'postalcode' => 'integer',
    ];
    public static $output_attrs = [];


    public function search($input, $output, Request $request)
    {

        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            null,
            $input);
        $inputval = $data[Constant::INPUTMAPS[$input]];

        $inputval = is_string($inputval) ? [$inputval] : $inputval;
//        $count = is_string($inputval) ? 1 : count($inputval);
        $inp = $input;
        $invalid_inputs = self::findInvalids($inputval, Constant::INPUTM[$inp]);

        $input_alias= in_array($input, array_keys(Constant::ALIASES)) ? Constant::ALIASES[$input] : $input;
        $output_alias = in_array($output, array_keys(Constant::OUTPUT_CHECK)) ? Constant::OUTPUT_CHECK[$output] : $output;
        if (!array_key_exists($input_alias, Constant::CAN)) {
            return $this->respondError("$input_alias is not valid", 422, 10002);
        }
        if (!in_array($output_alias, Constant::CAN[$input_alias])) {

            return $this->respondError("$output_alias is not valid", 422, 10003);
        }

//        dd($input_alias, $output, $inputval, $inp, $invalid_inputs);
        $response = Gnafservices::serach($input_alias, $output_alias, $inputval, $input, $invalid_inputs);

        return $this->respondArrayResult($response);
    }

    public function findInvalids($inputval, $inp)
    {
        $data = collect($inputval)->pluck($inp)->all();
        if ($inp == 'PostCode') {
            $invalids = [];
            foreach ($data as $datum) {
                $flag = preg_match(Constant::POSTCODE_PATTERN, $datum);
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
