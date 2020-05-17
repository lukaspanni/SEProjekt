<?php

abstract class Controller
{

    protected $view;
    protected $requestMethod;

    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    protected function escape_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    protected function escape_input_array($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->escape_input($value);
        }
        return $array;
    }

    public abstract function index();


}