<?php

define('SERVER_ROOT', str_replace("Webroot/index.php", "", $_SERVER["SCRIPT_FILENAME"]));
//require essential Files
require(SERVER_ROOT . 'Utility.php');
require(SERVER_ROOT . 'Config/Config.php');
require(SERVER_ROOT . 'Config/Database.php');
require(SERVER_ROOT . 'Controller/Controller.php');
require(SERVER_ROOT . 'View/View.php');
require(SERVER_ROOT . 'View/TemplateView.php');
require(SERVER_ROOT . 'View/ErrorView.php');
require(SERVER_ROOT . 'View/JSONView.php');
require(SERVER_ROOT . 'Model/Repository.php');
require(SERVER_ROOT . 'Model/Error.php');


require(SERVER_ROOT . 'Request.php');
require(SERVER_ROOT . 'Dispatcher.php');

session_start();

$dispatcher = new Dispatcher();
$dispatcher->dispatch();

?>
