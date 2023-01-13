<?php
require_once dirname(__DIR__)."/Config/constants.php";
require_once BASE_DIR.'/vendor/autoload.php';

if (PHP_SAPI == "cli"){
    require_once BASE_DIR.'/db/migration.php';
    die;
}

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();
use Config\Config;
use Core\Db;

try{
    $router = new \Core\Router();

    require_once BASE_DIR.'/routes/web.php';
    if(!preg_match('/assets/i', $_SERVER['REQUEST_URI'])){
        $router->dispatch($_SERVER['REQUEST_URI']);
       // d($router->getRoute());
    }

}catch (PDOException $exception){
    d("Exception: ", $exception->getMessage());
}catch (Exception $e){
    d($e->getMessage(). ' (File: '. $e->getFile(). ' in line '. $e->getLine().')');
}
