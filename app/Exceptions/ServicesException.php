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

    public function __construct($info, $input, $invalid_inputs = null, $error_code = null, $error_msg = null)
    {
        parent::__construct();
        $this->res_code = Constant::ERROR_RESPONSE_CODE;
        $this->res_message = trans('messages.custom.error.ResMsg');
//        dd($info, $input, $invalid_inputs, $error_code, $error_msg);
        $this->data = self::setData($info, $input, $invalid_inputs, $error_code, $error_msg);

    }

//each record has special error code/msg
    public function setData($info, $input, $invalid_inputs = null, $error_code = null, $error_msg = null)
    {
        $PorT = Constant::INPUTM[$input];
        $data = [];
        $area_code = '';
        foreach ($info as $i) {
            $client_row_id = $i['ClientRowID'];
            if (isset($i['AreaCode'])) {
                $area_code = $i['AreaCode'];
            }

            $temp = $i[$PorT];
            if (isset($invalid_inputs)) {
                $data[$temp] = ServicesResponse::succFalse($client_row_id, $input, $area_code,
                    $PorT, $temp, $invalid_inputs);
            } elseif (isset($error_msg) && isset($error_code)) {
                $data[$temp] = ServicesResponse::succFalse($i['ClientRowID'], $input, $area_code,
                    $PorT, $temp, null, $error_code, $error_msg);
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
        return array_values($this->data);
    }
}


