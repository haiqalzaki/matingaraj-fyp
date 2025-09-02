<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SessionHandler;

class Logout extends Controller 
{
    public function index() 
    {
        SessionHandler::logout();
        header('Location: '. $_ENV['HOME_URL'] .'/');
        exit();
    }
}