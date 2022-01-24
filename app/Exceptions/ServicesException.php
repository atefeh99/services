<?php


namespace App\Exceptions;

use App\Helpers\Constant;
use App\Helpers\ServicesResponse;
use Exception;

class ServicesException extends Exception
{
    protected $res_code;
    protected $res_message;
    protected $data;

    public function __construct(
        $info = null,
        $input = null,
        $invalid_inputs = [],
        $error_code = null,
        $error_msg1 = null,
        $error_msg2 = null,
        $res_code = null,
        $res_message = null,
        $data = null
    )
    {

        parent::__construct();
        $this->res_code = empty($res_code) ? Constant::ERROR_RESPONSE_CODE : $res_code;
        $this->res_message = empty($res_message) ? trans('messages.custom.error.ResMsg') : $res_message;
        $this->data = ($data == 'empty') ? null : self::setData($info, $input, $invalid_inputs, $error_code, $error_msg1, $error_msg2);
    }

//each record has special error code/msg
    public function setData($info, $input, $invalid_inputs = [], $error_code = null, $error_msg1 = null, $error_msg2 = null)
    {
        $PorT = Constant::INPUTM[$input];
        $area_code = '';
        $client_row_id = null;
        $data=[];
        foreach ($info as $i) {
            if (isset($i['ClientRowID'])) {
                $client_row_id = $i['ClientRowID'];
            }
            if (isset($i['AreaCode'])) {
                $area_code = $i['AreaCode'];
            }
            if (isset($i[$PorT])) {
                $temp = $i[$PorT];
            } elseif (isset($i['TransactionID'])) {
                $temp = $i['TransactionID'];
            } elseif (isset($i['FollowUpCode'])) {
                $temp = $i['FollowUpCode'];
            } else {
                $temp = $i['PostalCode'];
            }
            if (!empty($invalid_inputs) && in_array($temp, $invalid_inputs)) {
                $data[$temp] = ServicesResponse::succFalse($input, $area_code,
                    $PorT, $temp, $invalid_inputs, null, null, null, $client_row_id);
            } elseif ((isset($error_msg1) || isset($error_msg2)) && (isset($error_code))) {
                $data[$temp] = ServicesResponse::succFalse($input, $area_code,
                    $PorT, $temp, $invalid_inputs, $error_code, $error_msg1, $error_msg2, $client_row_id);
            } else {
                //if query result is null
                $data[$temp] = ServicesResponse::succFalse($input, $area_code,
                    $PorT, $temp, $invalid_inputs, null, null, null, $client_row_id);
            }
        }
        return $data;

    }

    public function getResMessage()
    {

        return $this->res_message;
    }

    public function getResCode()
    {
        return $this->res_code;
    }

    public function getData()
    {
        if (empty($this->data)) {
            return $this->data;
        }
        return array_values($this->data);
    }
}


