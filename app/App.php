<?php

namespace Application;

require_once "./config/Config.php";
require_once "./model/Model.php";

use Configuration\Config;
use Model\Model;

class App
{
    private $_config = null;

    public function __construct()
    {
        $this->Init();
    }

    private function Init()
    {
        Model::DatabaseInit();
    }
}

?>