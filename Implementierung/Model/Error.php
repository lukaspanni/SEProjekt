<?php

/**
 * Class ApplicationError
 */
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

/**
 * Class ErrorType - Enum
 * Contains possible Error Types
 */
abstract class ErrorType
{
    const LOGIN_ERROR = "Login Error";
    const INVALID_INPUT = "Invalid Input";
    const OTHER_ERROR = "Other Error";
    const ERROR_404 = "404 Not Found";
    const ERROR_501 = "501 Not Implemented";
}

?>