<?php


namespace App\Exceptions;


class AuthenticationException extends \Exception
{
    protected $error;
    protected $error_description;

    public function __construct($error, $error_description)
    {
        $this->error = $error;
        $this->error_description = $error_description;
    }

    public function getError()
    {

        return $this->error;
    }

    public function getErrorDescription()
    {
        return $this->error_description;
    }

}
