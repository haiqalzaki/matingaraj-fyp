<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Flasher;
use App\Helper\Response;
use App\Helper\Logger;
use App\Models\SessionHandler;
use App\Models\UserEntity;

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

        Logger::log('Error logged: Login failure from Home Controller!');
        Response::returnJSON(false, 'You have reached maximum login attempt! Please try again in 15 minutes.', ['lockout' => true]);
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
            Logger::log('Error logged: Credential failure from Home Controller!');
            Response::returnJSON(false, 'One or more fields cannot be empty.');
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
                Logger::log('Error logged: Login failure from Home Controller!');
                Response::returnJSON(false, 'Login failed. Wrong credential.');
            }

            unset($_SESSION['cubaLogin']);

            Response::returnJSON(true, 'Login successful! Redirecting to Dashboard...');
        } catch (\Exception $e) {
            Logger::log('Exception caught: '. $e->getMessage());
            Response::returnJSON(false, 'Failed logging in!');
        }
    }

    public function forgot() 
    {
        if ($this->isValidSession() === true) {
            header('Location: ' . $_ENV['HOME_URL'] . '/dashboard');
            exit();
        }

        $data['tajuk'] = "Forgot Password";
        $data['css'] = "forgot";
        
        $this->view('Partials/head-main', $data);
        $this->view('Templates/Home/forgot', $data);
        $this->view('Partials/footer-main');
        exit();
    }

    public function verifyKey($params = []) 
    {
        if ($this->isGET()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home/forgot');
            exit();
        }

        $email = $params['userEmail'] ?? null;
        $secretKey = $params['userKey'] ?? null;

        if (($email === null) || ($secretKey === null)) {
            Logger::log('Error logged: Credential failure from Home Controller!');
            Response::returnJSON(false, 'One or more fields cannot be empty.');
        }

        $appSecret = $_ENV['GLOBAL_KEY'];

        try {
            $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);  

            $sql = "SELECT 1 FROM garaj_user WHERE u_email = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();

            if ($count < 1 || $appSecret !== $secretKey) {
                Logger::log('Error logged: Credential failure from Home Controller!');
                Response::returnJSON(false, 'Invalid credentials!');
            }

            $_SESSION['password-change'] = [
                'is-verified' => true,
                'email' => $email
            ];

            $stmt->close();
            $db->close();

            Response::returnJSON(true, 'Login successful! Redirecting to set password page...');
        } catch (\Exception $e) {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($db)) {
                $db->close();
            }
            
            Logger::log('Exception caught: '. $e->getMessage());
            Response::returnJSON(false, 'Failed verifying!');
        }
    }

    public function passwordSetting() 
    {
        if ($this->isValidSession() === true) {
            header('Location: ' . $_ENV['HOME_URL'] . '/dashboard');
            exit();
        }

        if (!isset($_SESSION['password-change'])) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = "Password Setup";
        $data['css'] = "forgot";
        
        $this->view('Partials/head-main', $data);
        $this->view('Templates/Home/setpassword', $data);
        $this->view('Partials/footer-main');
        exit();
    }

    public function setPassword($params = []) 
    {
        if ($this->isGET()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        if (!isset($_SESSION['password-change'])) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $userEmail = $_SESSION['password-change']['email'] ?? null;
        $newPassword = $params['userPassword'] ?? null;

        if ($newPassword === null) {
            Response::returnJSON(false, 'Passwords field cannot be empty!');
        }

        if (strlen($newPassword) < 10) {
            Response::returnJSON(false, 'Password must be at least 10 characters.');
        }

        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        try {
            $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);  

            $sql = "UPDATE garaj_user SET u_password = ? WHERE u_email = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ss", $newPasswordHash, $userEmail);
            $stmt->execute();

            $stmt->close();
            $db->close();

            unset($_SESSION['password-change']);

            Response::returnJSON(true, 'Change password successful! Redirecting to login page...');
        } catch (\Exception $e) {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($db)) {
                $db->close();
            }
            
            Logger::log('Exception caught: '. $e->getMessage());
            Response::returnJSON(false, 'Failed to change password!');
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