<?php

class Request
{
    public $method;
    public $controller_name = "time";
    public $action = "index";
    public $parameter = [];

    public function __construct()
    {
        $this->method = $_SERVER["REQUEST_METHOD"];
        if ($this->method != "GET" && $this->method != "POST") {
            return_501_error();
        }
        $uri = $_SERVER["REQUEST_URI"];
        $uri_parts = array_slice(explode('/', $uri), 1);
        $uri_parts = array_values(array_filter($uri_parts));
        if (count($uri_parts) >= 1) {
            $this->controller_name = $uri_parts[0];
            if (count($uri_parts) >= 2) {
                $this->action = $uri_parts[1];
                if (count($uri_parts) > 2) {
                    for ($i = 2; $i < count($uri_parts); $i++) {
                        $this->parameter[] = $uri_parts[$i];
                    }
                }
            }
        }

    }
}

?>