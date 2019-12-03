<?php
namespace Model;

use Configuration\Config;
use Exception;
use mysqli;

abstract class Model
{
    protected $_connection = null;
    protected $_schema_name = "backend_test";
    protected $_table = "";
    static protected function Connect()
    {
        $config = Config::GetInstance();
        $connection = new mysqli($config->GetConfig("database.address"), $config->GetConfig("database.user"), $config->GetConfig("database.password"));
        if ($connection->connect_error) {
            throw new Exception("Unable to connect to the database");
        }
        return $connection;
    }

    protected function __construct()
    {
        $this->_connection = Model::Connect();
        $this->_connection->query("USE `$this->_schema_name`");
    }
    /** Starts the database */
    static public function DatabaseInit()
    {
        $connection = Model::Connect();
        /**Checks if the database is created */
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'backend_test'";
        
        $result = $connection->query($query);
        
        if(!$result->num_rows)
        {
            /** Create database and table */
            $connection->begin_transaction();
            try {
                $query = "create database if not exists `backend_test`";
                if (!$connection->query($query)) {
                    throw new Exception("Fail to create schema");
                }
                $query = "USE `backend_test`";
                $connection->query($query);
                $query = "CREATE TABLE `backend_test`.`products` (
                    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(200) NOT NULL,
                    `price` DOUBLE(6,2) NOT NULL DEFAULT 0,
                    `active` TINYINT(1) NOT NULL DEFAULT 1,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `id_UNIQUE` (`id` ASC) )";
                if (!$connection->query($query)) {
                    throw new Exception("Fail to create table Products");
                }
            } catch (Exception $ex) {
                $connection->rollback();
            }
            $connection->commit();
        }
        $connection->close();
    }

    protected function ExecuteQuery($query) : bool
    {
        if(empty($query)){
            return false;
        }
        $result = $this->_connection->query($query);
        return $result->num_rows > 0;
    }

    protected function __destruct()
    {
        $this->_connection->close();
    }
    private function Sanitize($var)
    {
        $sanitized = "";
        switch (\gettype($var)) {
            case "boolean":
                $sanitized = $this->_connection->real_escape_string($var);
                break;
            case 'integer':
                $sanitized = \filter_var($var, FILTER_SANITIZE_NUMBER_INT);
                break;
            case 'double':
                $sanitized = \filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT);
                break;
            case 'string':
                $sanitized = \filter_var($var, FILTER_SANITIZE_STRING);
                break;
            case 'array':
                foreach ($var as &$value) {
                    $value = $this->Sanitize($value);
                }
                return $var;
            case 'object':
            case 'unknown type':
            case 'resource':
            case 'NULL':
            default:
                $sanitized = $this->_connection->real_escape_string(\json_encode($var));
                break;
        }
        return "'$sanitized'";
    }
    protected function Save($data) : int
    {
        try{
            if (empty($data)) {
                return -1;
            }
            if (!empty($this->_table)) {
                $column_names = array_keys($data);
                $column_values = $this->Sanitize(array_values($data));
                $query = "";
                if (empty($data["id"])) {
                    $query = "INSERT INTO $this->_table(" . implode(',', $column_names) . ") values(" . implode(',', $column_values) . ")";
                }else 
                {
                    $update = [];
                    foreach($column_names as $idx => $value)
                    {
                        if (empty($value)) {
                            continue;
                        }
                        $update[] = $column_names[$idx]." = ".($column_values[$idx] ?? '');
                    }
                    $query = "UPDATE $this->_table SET ".implode(',', $update)." WHERE id = {$data["id"]} ";
                }
                $result = $this->_connection->query($query);
                
                if($result)
                {
                    return !empty($data["id"]) ? $data["id"] : \mysqli_insert_id($this->_connection);
                }
            }
        }
        catch(Exception $ex)
        {
            /** log error */
            echo $ex->getMessage();
        }
        return -1;
    }

    protected function FetchAssociative($query) : array
    {
        $result_array = [];
        try
        {
            if ($result = $this->_connection->query($query)) {
                $result_array = $result->fetch_all(MYSQLI_ASSOC);
            }
        }catch(Exception $ex)
        {
            /** Log error */
            echo $ex->getMessage();
        }
        return $result_array;
    }

    protected function ReadOne($id) : array
    {
        if(empty($id))
        {
            return [];
        }
        try
        {
            $query = "SELECT * FROM $this->_table WHERE id = $id";
            $result = $this->_connection->query($query);
            if($result && $result->num_rows > 0)
            {
                return $result->fetch_assoc();
            }
        }catch(Exception $ex)
        {
            /** log error */
            echo $ex->getMessage();
        }
        return [];
    }
}


?>