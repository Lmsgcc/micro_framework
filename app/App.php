<?php

namespace Application;

require_once "./config/Config.php";
require_once "./model/Model.php";

use phpFastCache\CacheManager;
use Configuration\Config;
use Model\Model;

class App
{
    private $_config = null;
    private $_cache = null;
    public function __construct()
    {
        $this->_config = Config::GetInstance();
        session_start();
        $this->Init();
        $this->LoadLibraries();
    }
    public function GetConfig()
    {
        return $this->_config;
    }

    public function GetFromCache($cache_key)
    {
        if($this->_cache === null)
        {
            return null;
        }
        $cached_value = $this->_cache->getItem($cache_key);
        return $cached_value->isHit() ? $cached_value->get() : null;
    }

    public function SetToCache($cache_key, $value)
    {
        
        if($this->_cache === null)
        {
            return;
        }
        $cached = $this->_cache->getItem($cache_key);
        $cached->set($value)->expiresAfter(filter_var($this->_config->GetConfig("cache.expires"), FILTER_SANITIZE_NUMBER_INT)) ;
        $this->_cache->save($cached);
    }

    private function LoadLibraries()
    {
        if ($this->_config->GetConfig("cache.active"))
        {
            /**
             * Get active driver
             */
            $drivers = $this->_config->GetConfig("cache.driver");
            $this->_cache = CacheManager::getInstance($drivers ?? null);
        }
    }
    private function Init()
    {
        Model::DatabaseInit();
    }

    public function __destruct()
    {
        session_destroy();
     }
}

?>