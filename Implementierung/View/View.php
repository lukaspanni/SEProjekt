<?php


class View
{
    protected $model;
    protected $additionalData = array();

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function setModel($model){
        $this->model = $model;
    }

    public function render()
    {

    }

    public function set($key, $value)
    {
        $this->additionalData[$key] = $value;
    }

    //Use PropertyOverloading to access data
    public function __get($key)
    {
        if (array_key_exists($key, $this->additionalData)) {
            return $this->additionalData[$key];
        }
        return null;
    }
}