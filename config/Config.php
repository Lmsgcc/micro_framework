<?php 
namespace Configuration;

use Exception;

class Config
{

    static private $_instance = null;

    private $_config_path = "./config.xml";
    private $_config = [];
    private function __construct()
    {
        $config_file = \file_get_contents($this->_config_path);
        $config_object = simplexml_load_string($config_file);
        $json = \json_encode($config_object);
        $this->_config = json_decode($json);
    }

    static public function GetInstance()
    {
        if (!isset(Config::$_instance)) {
            Config::$_instance = new Config();
        }
        return Config::$_instance;
    }
    private function get_configuration_value(array $value, object $obj = null) : string
    {
        $obj = $obj ?? $this->_config;
        if (!\is_array($value)) {
            if (\is_string($value)) {
                return $obj->$value;
            }
            throw new Exception("Configuration not found");
        }
        if(empty($value) && !empty($obj))
        {
            return \json_encode($obj);
        }
        $key = array_shift($value);
        if (empty($key)) {
            return "";
        }
        if (\is_string($obj->$key)) {
            return $obj->$key;
        }
        return $this->get_configuration_value($value, $obj->$key);
    }
    public function GetConfig(string $config_name)
    {
        try {
            $result = $this->get_configuration_value(\explode(".", $config_name));
            $json = \json_decode($result, true);
            if(\json_last_error() == JSON_ERROR_NONE)
            {
                $result = $json;
            }
            return empty($result) ? "" : $result;
        } catch (Exception $ex) {
            /** log error */
            echo $ex->getMessage() . " - $config_name";
        }
        return "";
    }
}
