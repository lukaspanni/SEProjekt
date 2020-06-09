<?php

/**
 * Class Request
 * Gets HTTP-Request-Method, Controller-Function (Action) and parameters for Controller function
 */
class Request
{
    private $method;
    private $controllerName = "time"; // default-controller
    private $action = "index"; // default-action
    private $parameter = [];

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        if ($this->method != "GET" && $this->method != "POST") {
            return_501_error();
        }
        $uri = $_SERVER["REQUEST_URI"];
        $uriParts = array_slice(explode('/', $uri), 1);
        $uriParts = array_values(array_filter($uriParts));
        //get controller-function and parameter
        if (count($uriParts) >= 1) {
            $this->controllerName = $uriParts[0];
            if (count($uriParts) >= 2) {
                $this->action = $uriParts[1];
                // parameter
                if (count($uriParts) > 2) {
                    for ($i = 2; $i < count($uriParts); $i++) {
                        $this->parameter[] = $uriParts[$i];
                    }
                }
            }
        }

    }

    public function getAction()
    {
        return $this->action;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParameter()
    {
        return $this->parameter;
    }
}

?>