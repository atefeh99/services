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

    public function __construct($info, $input,$error_msg,$error_code)
    {
        parent::__construct();

        $this->res_code = Constant::ERROR_RESPONSE_CODE;
        $this->res_message = trans('messages.custom.error.ResMsg');
        $this->data = self::setData($info, $input,$error_msg,$error_code);

    }

    public function setData($info, $input, $error_msg,$error_code)
    {

        $PorT = Constant::INPUTM[$input];
        $data = [];
        $area_code = '';
        foreach ($info[Constant::INPUTMAPS[$input]] as $i) {

            if (isset($i['AreaCode'])) {
                $area_code = $i['AreaCode'];
            }
            $temp = $i[$PorT];
            $data[$temp] = ServicesResponse::succFalse($i['ClientRowID'], $input, $area_code, $PorT, $temp, $error_msg, '', $error_code);
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


