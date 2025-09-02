<?php

namespace App\Core;

class Controller 
{
    public function view($view, $data = []) 
    {
        $path = rtrim(str_replace('\\', '/', dirname(__DIR__) . '/Views/'), '/') . '/';

        require_once $path . $view . '.php';
    }

    public function isPOST() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            return true;
        }
    }

    public function isGET() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') 
        {
            return true;
        }
    }

    public function isValidSession() 
    {
        if (isset($_SESSION['id'])) {
            return true;
        }
    }

    public function isValidAdmin() 
    {
        if (isset($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] === true)) 
        {
            return true;
        }
    }
}
