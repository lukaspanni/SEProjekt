<?php

/**
 * Class Controller
 * Base Class for Controllers
 */
abstract class Controller
{

    protected $view;
    protected $requestMethod;

    public function __construct($requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * removing/escaping not allowed characters
     * @param $data
     * @return string
     */
    protected function escape_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * removing/escaping not allowed characters for every array-entry
     * @param $dataArray array,
     * @return mixed
     */
    protected function escape_input_array($dataArray)
    {
        foreach ($dataArray as $key => $value) {
            $dataArray[$key] = $this->escape_input($value);
        }
        return $dataArray;
    }

    /**
     * Default Controller-Function
     */
    public abstract function index();

}