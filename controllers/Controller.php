<?php 
namespace Controllers;

use Configuration\Config;
use Exception;

abstract class Controller
{
    protected $_data = [];
    protected $_config = null;
    protected $_url = "";
    protected $_controller = "";
    protected $_action = "";
    protected $_post = [];
    protected $_model = null;
    protected function __construct()
    {
        $this->_controller = $_GET["controller"] ?? "start";
        $this->_action = $_GET["action"] ?? "index";
        if(!empty($_POST))
        {
            $this->_post = $_POST;
        }
    }

    protected function LoadView($data = [])
    {
        $exception = new Exception();
        $trace = $exception->getTrace();
        $called = $trace[1];
        $class = \explode("\\", $called["class"]);
        $class = $class[\sizeof($class) - 1] ?? "";
        $function = $called["function"] ?? "";
        $this->_config = Config::GetInstance()->GetConfig("view");
        $this->_url = "./index.php?";
        require_once "./views/header.php";
        if ($class && $function) {
            require_once "./views/$class/$function.php";
        }
        require_once "./views/footer.php";
    }

    protected function RedirectInternal($controller, $action, $params = [])
    {
        $url = "./index.php?controller=$controller&action=$action";
        if(!empty($params))
        {
            $url .= "&".implode("&", $params);
        }
        \header("Location:".$url, true, 303);
        die();
    }

    protected function LoadModule($module_name)
    {
        include "./views/modules/$module_name.php";
    }
}
?>