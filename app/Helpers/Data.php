<?php

namespace App\Helpers;

use Errors;

class Data
{
    public $ClientRowID;
    public $Postcode;
    public $TelephoneNo;
    public $AreaCode;
    public $Succ;
    public $Result;
    public $Errors;


    public function __construct($client_row_id,
                                $succ,
                                $result,
                                $errors = null,
                                $postcode = null,
                                $telephone = null,
                                $area_code = null)
    {
        $this->ClientRowID = $client_row_id;
        if (isset($postcode)) {
            $this->Postcode = $postcode;

        } elseif (isset($telephone)) {
            $this->TelephoneNo = $telephone;
            $this->AreaCode = $area_code;
        }
        $this->Succ = $succ;
        $this->Result = $this->setResult($result);;
        $this->Errors = $this->setErrors($errors);
    }

    public function setErrors($errors)
    {
        if (isset($errors)) $this->errors = new Errors($errors);
        else $this->errors = null;
    }

    public function setResult($result)
    {
        if (isset($result)) {
            $this->Result = $result;
            $this->Result->ErrorCode = 0;
            $this->Result->ErrorMessage = null;
        } else {
            $this->errors = null;
        }
    }
}
