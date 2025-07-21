<?php
namespace Core;

class App {
    public static function run() {
        $url = $_GET['url'] ?? 'stock/index';
        $url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

        $controllerName = 'App\\Controllers\\' . ucfirst($url[0]) . 'Controller';
        $method = $url[1] ?? 'index';
        $params = array_slice($url, 2);

        if (class_exists($controllerName)) {
            $controller = new $controllerName;

            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                echo "Método '$method' não encontrado.";
            }
        } else {
            echo "Controller '$controllerName' não encontrado.";
        }
    }
}
