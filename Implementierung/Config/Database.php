<?php

/**
 * Class Database
 */
class Database
{
    private static $connection = null;

    /**
     * @param $host string: Database host
     * @param $db string: Database
     * @param $usr string: Database user
     * @param $pwd string: user password
     * @return PDO|null
     */
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