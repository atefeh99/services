<?php

namespace App\Http\Controllers;


use App\Exceptions\ServicesException;
use App\Exceptions\UnauthorizedUserException;
use App\Helpers\Constant;
use App\Helpers\Scopes;
use App\Models\Post;
use App\Http\Services\Gnafservices;
use App\Models\Province;
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

    public function generateCertificateByTxn(Request $request)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 1000);
        }
        $input = 'Postcode';
        $output = 'GenerateCertificateByTxn';
        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            null,
            $input);
        $scopes = null;
        if (!empty($request->header("x-scopes"))) {
            $scopes = Scopes::getScopes($request->header("x-scopes"));
        }
        $invalid_inputs = self::findInvalids($data, 'PostCode', 2);
        $output_alias = in_array($output, array_keys(Constant::OUTPUT_CHECK)) ? Constant::OUTPUT_CHECK[$output] : $output;
        $input_alias = array_key_exists($input, Constant::ALIASES) ? Constant::ALIASES[$input] : $input;

        $result = Gnafservices::generateCertificateByTxn($data,$user_id, $input, $invalid_inputs, $output_alias, $scopes,$input_alias);
        return $this->respondArrayResult($result);
    }

    public function trackRequest(Request $request)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 2000);
        }
        $input = 'Postcode';
//        $output = 'trackRequest';
        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            null,
            $input);
        $result = Gnafservices::trackRequest($data,$input);
        return $this->respondArrayResult($result);
    }

    public function requestPostCode(Request $request)
    {
        $input = 'Postcode';
        $output = 'requestPostCode';
        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            null,
            $input);
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 3000);
        }
        $scopes = null;
        if (!empty($request->header("x-scopes"))) {
            $scopes = Scopes::getScopes($request->header("x-scopes"));
        }
        $result = Gnafservices::requestPostCode($data, $scopes, $user_id,$input);
        return $this->respondArrayResult($result);
    }

//    public function reqStatus(Request $request)
//    {
//        $input = 'Postcode';
//        $output = 'ReqStatus';
//        $data = self::checkRules(
//            $request->all(),
//            __FUNCTION__,
//            null,
//            $input);
//        $error_msg1 = trans('messages.custom.error.2117');
//        $scopes = null;
//        if (!empty($request->header("x-scopes"))) {
//            $scopes = Scopes::getScopes($request->header("x-scopes"));
//        }
//        $inputval[0] = $data;
//        $invalid_inputs = self::findInvalids($data, 'PostalCode', 2);
//        $input_alias = array_key_exists($input, Constant::ALIASES) ? Constant::ALIASES[$input] : $input;
//        $output_alias = in_array($output, array_keys(Constant::OUTPUT_CHECK)) ? Constant::OUTPUT_CHECK[$output] : $output;
//        if (!array_key_exists($input_alias, Constant::CAN)) {
//            throw new ServicesException($inputval, $input, [], 2117, $error_msg1);
//        }
//        if (!in_array($output_alias, Constant::CAN[$input_alias])) {
//            throw new ServicesException($inputval, $input, [], 2118, $error_msg1);
//        }
//        $response = Gnafservices::serach($input_alias, $output_alias, $inputval, $input, $invalid_inputs, $scopes,/*$user_id*/);
//
//    }


    public function search($input, $output, Request $request)
    {
        $user_id = $request->header('x-user-id');

        if (!isset($user_id)) {
            throw new UnauthorizedUserException(trans('messages.custom.unauthorized_user'), 4000);
        }

        $data = self::checkRules(
            $request->all(),
            __FUNCTION__,
            null,
            $input);
        $error_msg1 = trans('messages.custom.error.2117');

        //zamani ke dar url darkhast eshtebah bashad
        $scopes = null;
        if (!empty($request->header("x-scopes"))) {
            $scopes = Scopes::getScopes($request->header("x-scopes"));
        }
        if (isset($data[Constant::INPUTMAPS[$input]])) {
            $inputval = $data[Constant::INPUTMAPS[$input]];
        } elseif ($input == 'Telephone') {
            $info = $data[Constant::INPUTMAPS['Postcode']];
            throw new ServicesException($info, 'Postcode', null, 2117, $error_msg1, null);
        } elseif ($input == 'Postcode') {
            $info = $data[Constant::INPUTMAPS['Telephone']];
            throw new ServicesException($info, 'Telephone', null, 2117, $error_msg1, null);
        }

        $inputval = is_string($inputval) ? [$inputval] : $inputval;
//        dd($inputval);
//        $count = is_string($inputval) ? 1 : count($inputval);
        $inp = $input;
        $invalid_inputs = self::findInvalids($inputval, Constant::INPUTM[$inp]);
//        $input_alias = in_array($input, array_keys(Constant::ALIASES)) ? Constant::ALIASES[$input] : $input;
        $input_alias = array_key_exists($input, Constant::ALIASES) ? Constant::ALIASES[$input] : $input;
        $output_alias = in_array($output, array_keys(Constant::OUTPUT_CHECK)) ? Constant::OUTPUT_CHECK[$output] : $output;
//        $error_msg1 = trans('messages.custom.error.2117');
        if (!array_key_exists($input_alias, Constant::CAN)) {
            throw new ServicesException($inputval, $input, [], 2117, $error_msg1);
        }
        if (!in_array($output_alias, Constant::CAN[$input_alias])) {
            throw new ServicesException($inputval, $input, [], 2118, $error_msg1);
        }
        $response = Gnafservices::serach($input_alias, $output_alias, $inputval, $input, $invalid_inputs, $scopes,$user_id);

        return $this->respondArrayResult($response);
    }

    public function findInvalids($inputval, $inp, $type = 1)
    {
        $invalids = [];

        switch ($type) {
            case 1:
                $data = collect($inputval)->pluck($inp)->all();
                if ($inp == 'PostCode') {
                    foreach ($data as $datum) {
                        $flag = preg_match(Constant::POSTCODE_PATTERN, $datum);
                        if ($flag == 0) {
                            $invalids[] = $datum;
                        }
                    }
                    return $invalids;
                } else {
                    //todo tel regex
                    return $invalids;
                }
            case 2:
                $flag = preg_match(Constant::POSTCODE_PATTERN, $inputval[$inp]);
                if ($flag == 0) {
                    $invalids[] = $inputval[$inp];
                }
                return $invalids;

        }
    }


}
