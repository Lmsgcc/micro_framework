<?php

require_once __DIR__."/app/App.php";
require_once __DIR__."/controllers/Controller.php";
require_once __DIR__."/model/Model.php";
require_once __DIR__."/vendor/autoload.php";


use Application\App;
use Configuration\Config;

$app = new App();
$GLOBALS["App"] = &$app;

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