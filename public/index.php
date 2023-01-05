<?php
use Config\Config;
require_once dirname(__DIR__)."/Config/constants.php";
require_once BASE_DIR.'/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(BASE_DIR);
$dotenv->load();
try{
    $pdo = new PDO(
        "mysql:host=".Config::get("db.host")."; dbname=".Config::get("db.database"),
        Config::get("db.user"),
        Config::get("db.password")
    );

    d($pdo);
}catch (PDOException $exception){
    d("Exception: ", $exception->getMessage());
}catch (Exception $e){
    d($e->getMessage(). ' (File: '. $e->getFile(). ' in line '. $e->getLine().')');
}
