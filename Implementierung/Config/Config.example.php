<?php


class Config
{
    private $_DB_CONFIG = [
        "DB_HOST" => "",
        "DB_NAME" => "",
        "DB_USER" => "",
        "DB_PWD" => ""
    ];

    private $_PAGES = [
        "USER" => 0,
        "PROJECT" => 1,
        "TIME" => 2,
        "LOGIN" => 3
    ];

    private static $instance = null;

    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct()
    {
        $this->loadConfig();
    }

    private function loadConfig()
    {
        //
    }

    public function getDBConfig()
    {
        return $this->_DB_CONFIG;
    }

    public function getPages()
    {
        return $this->_PAGES;
    }

}


?>