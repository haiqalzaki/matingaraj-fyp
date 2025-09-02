<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Response;
use App\Helper\Logger;
use App\Models\SessionHandler;

class Home extends Controller 
{
    private $tajuk = 'Home';
    private $css = 'home';
    private $masaReset = 900;
    private $cubaMax = 5;

    public function index() 
    { 
        if ($this->isValidSession() === true) {
            header('Location: ' . $_ENV['HOME_URL'] . '/dashboard');
            exit();
        }

        // uncomment disini untuk debugging (max percubaan capai)
        // unset($_SESSION['cubaLogin']);
        // var_dump($_SESSION['cubaLogin']);

        if (isset($_SESSION['cubaLogin'])) 
            {
                if ($_SESSION['cubaLogin']['maxCuba'] === true) 
                    {
                        $this->resetAttemptAfterPresetTime();
                        $this->max();
                    }
            }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        
        $this->view('Partials/head-main', $data);
        $this->view('Templates/Home/index', $data);
        $this->view('Partials/footer-main');
        exit();
    }

    public function max() 
    {   
        if (!isset($_SESSION['cubaLogin'])) 
            {
                header('Location: ' . $_ENV['HOME_URL'] . '/home');
                exit();
            }
        
        if ($this->resetAttemptAfterPresetTime()) 
            {
                header('Location: ' . $_ENV['HOME_URL'] . '/home');
                exit();
            }

        $data['tajuk'] = "Lockout";
        $data['css'] = $this->css;
        
        $this->view('Partials/head-main', $data);
        $this->view('Templates/Home/lockout', $data);
        $this->view('Partials/footer-main');
        exit();
    }

    public function lockout() 
    {
        if ($this->isGET()) {
            $this->index();
        }

        Response::returnJSON(false, 'You have reached maximum login attempt! Please try again in 15 minutes.', ['lockout' => true]);
        Logger::log('Error logged: Login failure from Home Controller!');
        exit();
    }

    public function login($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        $name = $params['userNameEmail'] ?? null;
        $password = $params['password'] ?? null;

        $this->resetAttempt();
        $this->resetAttemptAfterPresetTime();
        
        if (($name === null) || ($password === null)) {
            Response::returnJSON(false, 'One or more fields cannot be empty.');
            Logger::log('Error logged: Credential failure from Home Controller!');
            exit();
        }  

        try {
            $login = new SessionHandler($name, $password); 
            if ($login->login() === false) {
                
                $_SESSION['cubaLogin']['percubaan']++;

                if (($_SESSION['cubaLogin']['percubaan'] >= $this->cubaMax) && ((time() - $_SESSION['cubaLogin']['masaCuba']) < $this->masaReset)) 
                    {
                        $_SESSION['cubaLogin']['maxCuba'] = true;

                        $this->lockout();
                    }
                Response::returnJSON(false, 'Login failed. Wrong credential.');
                Logger::log('Error logged: Login failure from Home Controller!');
                exit();
            }

            unset($_SESSION['cubaLogin']);

            Response::returnJSON(true, 'Login successful! Redirecting to Dashboard...');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed logging in!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    private function resetAttempt() 
    {
        if (!isset($_SESSION['cubaLogin'])) 
            {
                $_SESSION['cubaLogin'] = [
                    'percubaan' => 0,
                    'masaCuba' => time(),
                    'maxCuba' => false
                ];

                return true;
            }

        return false;
    }

    private function resetAttemptAfterPresetTime() 
    {
        if (time() - $_SESSION['cubaLogin']['masaCuba'] > $this->masaReset) 
            {
                $_SESSION['cubaLogin']['percubaan'] = 0;
                $_SESSION['cubaLogin']['masaCuba'] = time(); // reset masa
                $_SESSION['cubaLogin']['maxCuba'] = false;

                return true;
            }

        return false;
    }
}