<?php


class Config
{
    private $_DB_CONFIG = [
        "DB_HOST" => "",
        "DB_NAME" => "",
        "DB_USER" => "",
        "DB_PWD" => ""
    ];

    private static $instance = null;

    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getDBConfig()
    {
        return $this->_DB_CONFIG;
    }

}


?>