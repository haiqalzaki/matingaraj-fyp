<?php

namespace App\Core;

use App\Core\Request;

class Router
{
    private $defaultController = "Home";
    private $defaultAction = "index";
    private $defaultParams = [];

    protected $routes = [];
    protected $controller;
    protected Request $request;

    public function __construct()
    {
        $parseUrl = $this->parseURL();

        if (empty($parseUrl)) {
            $parseUrl = [$this->defaultController, $this->defaultAction];
        }

        $controllerName = ucfirst($parseUrl[0] ?? $this->defaultController);

        if (!$this->checkControllerExists($controllerName)) {
            $controllerName = $this->defaultController;
        }

        $this->controller = $this->getValidatedController($controllerName);
        unset($parseUrl[0]);

        $action = isset($parseUrl[1]) ? $parseUrl[1] : $this->defaultAction;
        if (!$this->checkMethodExists($this->controller, $action)) {
            $action = $this->defaultAction;
        }
        unset($parseUrl[1]);

        $params = !empty($parseUrl) ? array_values($parseUrl) : $this->defaultParams;

        $this->routes = [
            'controller' => $controllerName,
            'action' => $action,
            'params' => $params
        ];
        
        $this->request = new Request($this->controller, $action, $params);
    }

    public function checkControllerExists(string $controller)
    {
        $path = __DIR__ . '/../Controllers/' . $controller . 'Controller.php';
        return file_exists($path);
    }

    public function checkMethodExists(object $object, string $method)
    {
        return method_exists($object, $method);
    }

    public function getValidatedController(string $controller): object
    {
        $controllerFile = __DIR__ . '/../Controllers/' . $controller . 'Controller.php';
        require_once $controllerFile;
        $className = 'App\\Controllers\\' . $controller;
        return new $className;
    }

    private function parseURL()
    {
        if (isset($_GET['url'])) 
        {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return null;
    }
}
