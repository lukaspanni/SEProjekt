<?php


class ApplicationError
{

    private $errorType;
    private $errorMessage;
    private $httpStatusCode;

    public function getErrorType(){
        return $this->errorType;
    }

    public function getErrorMessage(){
        return $this->errorMessage;
    }

    public function getHttpStatusCode(){
        return $this->httpStatusCode;
    }

    public function __construct($errorType, $errorMessage, $httpStatusCode=200)
    {
        $this->errorType = $errorType;
        $this->errorMessage = $errorMessage;
        $this->httpStatusCode = $httpStatusCode;
    }

}

abstract class ErrorType
{
    const LOGIN_ERROR = "Login Error";
    const INVALID_INPUT = "Invalid Input";
    const OTHER_ERROR = "Other Error";
}

?>