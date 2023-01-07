<?php
namespace Config;
class Config
{
    protected static array $configs = [];
    private static Config|null $instance = null;

    private function __construct(){

    }

    protected static function getInstance(): self
    {
        if (is_null(self::$instance)){
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected static function getParamsConfig(){
        if(empty(self::$configs)){
            self::$configs = require_once BASE_DIR.'/Config/configs.php';
        }

        return self::$configs;

    }

    /**
     * @throws \Exception
     */
    public static function get($name = null):mixed
    {
        self::getInstance();
        if ((bool) $name) {
            $keys = explode(".", $name);
        }else{
            throw new \Exception('Enter any config value');
        }
        $paramsConfig = self::getParamsConfig();

        $configValue = [];
        foreach ($keys as $value){
            if(empty($configValue) && array_key_exists($value, $paramsConfig)){
                $configValue = $paramsConfig[$value];
            }elseif(is_array($configValue) && array_key_exists($value, $configValue)){
                $configValue= $configValue[$value];
            }else{
                throw new \Exception('Enter correct config setting');
            }

        }
        return $configValue;
    }

}