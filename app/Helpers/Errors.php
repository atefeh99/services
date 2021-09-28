<?php

namespace App\Helpers;

class Errors
{
    public $ErrorCode;
    public $ErrorMessage;

    public function __construct($error_code, $error_message)
    {
        $this->ErrorCode = $error_code;
        $this->ErrorMessage = $error_message;
    }

}
