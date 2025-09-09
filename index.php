<?php
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define("BASE_URL", "/POC_PHP");


require_once __DIR__."/Models/ConnexionBD.php";
include_once __DIR__.'/Models/User.php';
include_once __DIR__.'/Models/Book.php';
include_once __DIR__.'/Models/Media.php';
include_once __DIR__.'/Models/Album.php';
include_once __DIR__.'/Models/Song.php';
include_once __DIR__.'/Models/Movie.php';

session_start();

$actionParam = $_GET['action'] ?? null;

if(isset($_GET['action']) && !empty($_GET['action'])){
    $action = explode('/', $_GET['action']);
    $controllerName = ucfirst(strtolower($action[0]))."Controller";
    $method = isset($action[1]) ? $action[1] : "index";
    $objectNumber = isset($action[2]) ? array_slice($action, 2) : null;

    if (file_exists("Controllers/".$controllerName .".php")) {
        require_once("Controllers/".$controllerName .".php");
        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            if (method_exists($controller, $method)) {
                if($objectNumber != null){
                    $params = $objectNumber;
                }else{
                    $params = [];
                }
                call_user_func_array([$controller, $method], array_values($params));
            } else {
                require_once('Controllers/ErrorController.php');
                $controller = new ErrorController();
                $controller->noFoundError();
            }
        } else {
            require_once('Controllers/ErrorController.php');
            $controller = new ErrorController();
            $controller->noFoundError();
        }
    } else {
        require_once('Controllers/ErrorController.php');
        $controller = new ErrorController();
        $controller->noFoundError();
    }
}else{
    require_once('Controllers/HomeController.php');
    $controller = new HomeController();
    $controller->home();
}