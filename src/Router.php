<?php

namespace Firework\Router;

use function Composer\Autoload\includeFile;

class Router
{
    static function route()
    {
        $controllerName = "index";
        $actionName = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routes[1]))
        {
            $controllerName = $routes[1];
        }

        if (!empty($routes[2]))
        {
            $actionName = $routes[2];
        }

        $modelName = $controllerName . 'Model';
        $controllerName = $controllerName . "Controller";

        $modelFile = strtolower($modelName) . '.php';
        $modelPath = "/src/models/" . $modelFile;

        if (file_exists($modelPath))
        {
            include $modelPath;
        }


        $controllerFile = $controllerName . '.php';
        $controllerPath = "src/controllers/" . $controllerFile;

        if (file_exists($controllerPath))
        {
            require_once $controllerPath;
        } else {
            Router::errorPage404();
        }

        $controller = new $controllerName;
        $action = $actionName;

        if (method_exists($controller, $action))
        {
            $controller->$action();
        } else {
            Router::errorPage404();
        }
    }

    static function errorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . 'e404');
    }
}