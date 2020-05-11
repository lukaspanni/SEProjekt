<?php


class Database
{
    private static $connection = null;

    public static function getConnection($host, $db, $usr, $pwd)
    {
        if(static::$connection == null) {
            try {
                return new PDO('mysql:host=' . $host . ';dbname=' . $db, $usr, $pwd);
            } catch (PDOException $e) {
                die();
            }
        }else{
            return static::$connection;
        }
    }
}

?>