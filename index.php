<?php

require_once "app/App.php";
require_once "controllers/Controller.php";
require_once "model/Model.php";

use Application\App;
use Configuration\Config;

$app = new App();

$config = Config::GetInstance();

$controller = $_GET["controller"] ?? "start";
$action = $_GET["action"] ?? "index";
try {
    $controller = ucfirst($controller);
    require_once "controllers/$controller.php";
    require_once "model/".$controller."Model.php";
    $controller = "\\Controllers\\$controller";
    $controller_object = new $controller();
    unset($_GET["controller"], $_GET["action"]);
    
    call_user_func_array([$controller_object, $action], $_GET);
} catch (Exception $ex) {
    echo $ex->getMessage();
}



?>