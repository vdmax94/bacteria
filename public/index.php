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
    dd(Db::getConnect());
}catch (PDOException $exception){
    d("Exception: ", $exception->getMessage());
}catch (Exception $e){
    d($e->getMessage(). ' (File: '. $e->getFile(). ' in line '. $e->getLine().')');
}
