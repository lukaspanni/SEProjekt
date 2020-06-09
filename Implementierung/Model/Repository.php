<?php


/**
 * Class Repository
 * adding, selecting and updating data from database
 */
abstract class Repository
{
    protected $dbConnection;

    public function __construct()
    {
        $this->dbConnection = call_user_func_array('Database::getConnection', Config::$_DB_CONFIG);
    }

    public abstract function add($object);

    public abstract function update($object);

    public abstract function getCount();
}


?>