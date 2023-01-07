<?php

namespace Core;

use PDO;
use Config\Config;

class Db
{
    protected function __construct()
    {
    }

    protected static PDO|null $connect = null;

    public static function getConnect ():PDO
    {
        if (is_null(static::$connect)) {
            $dsn = "mysql:host=".Config::get('db.host').'; dbname='.Config::get('db.database');
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION
            ];

            static::$connect = new PDO ($dsn, Config::get('db.user'), Config::get('db.password'), $options);
        }

        return static::$connect;

    }

}