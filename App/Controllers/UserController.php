<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserEntity;
use App\Models\UserManager;
use App\Helper\Response;
use App\Helper\Logger;

class User extends Controller 
{
    private $tajuk = 'User';
    private $css = 'user';

    public function index() 
    {
        if (!$this->isValidSession() || !$this->isValidAdmin()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
        
        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/User/index', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit();
    }

    public function add($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $user = [
            "name" => $params['userName'] ?? null,
            "nameSearch" => strtolower(str_replace(' ','', $params['userName'] ?? null)),
            "password" => $params['userPassword'] ?? null,
            "email" => $params['userEmail'] ?? null,
            "phone" => $params['userPhone'] ?? null,
            "isAdmin" => $params['userRole'] ?? null,
        ];

        // Pengesahan input pengguna
        foreach ($user as $key => $value) {
            if ($key === 'name' || $key === 'password' || $key === 'email' || $key === 'phone' ) {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    exit();
                }
                if ($value === '') {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty field!');
                    exit();
                } 
            }   
            if ($key === 'name') {
                if (strpos($value, ' ')) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot contain spaces!');
                    exit();
                }
            }
            if ($key === 'isAdmin') {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, 'Role cannot be empty!');
                    exit();
                } 
            }
        }

        // Role Type Cast
        if ($user['isAdmin'] === '1' || $user['isAdmin'] === '0') {
            $user['isAdmin'] = (int) $user['isAdmin'];
        }

        // Hash password
        $user['password'] = password_hash($params['userPassword'], PASSWORD_BCRYPT);

        // Database query
        try {
            $userInsert = new UserEntity(); 
            $userInsert->build($user);
            if (!$userInsert->create()) {
                Response::returnJSON(false, 'Register failed. Username or email already exist.');
                exit();
            }
            Response::returnJSON(true, 'Register successful!');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed registering!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function update($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $user = [
            "id" => (int) $params['userID'] ?? null,
            "name" => $params['userName'] ?? null,
            "nameSearch" => strtolower(str_replace(' ','', $params['userName'] ?? null)),
            "email" => $params['userEmail'] ?? null,
            "phone" => $params['userPhone'] ?? null,
            "isAdmin" => $params['userRole'] ?? null,
        ];

        try {
            $userUpdate = new UserEntity($user['id']);
            $userUpdate->setName($user['name']);
            $userUpdate->setNameSearch($user['nameSearch']);
            $userUpdate->setEmail($user['email']);
            $userUpdate->setPhone($user['phone']);
            $userUpdate->setRole($user['isAdmin']);

            if (!$userUpdate->save()) {
                Response::returnJSON(false, 'Update failed. Username or email already exist.');
                exit();
            }
            Response::returnJSON(true, "Success updating user data!");
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed to update user data from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        } 
    }

    public function delete($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $id = (int) $params['userID'] ?? null;

        try {
            $userDelete = new UserEntity($id);
            $userDelete->delete();

            Response::returnJSON(true,'User deleted successfully.');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed delete user from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function get($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $id = (int) isset($params[0]) ? $params[0] : null;

        if ($id === null) {
            Response::returnJSON(false,'Invalid request.', $params);
            exit();
        }

        try {
            $user = new UserEntity($id);
        
            $data['username'] = $user->getName();
            $data['email'] = $user->getEmail();
            $data['phone'] = $user->getPhone();
            $data['role'] = $user->getRole();

            Response::returnJSON(true, "Success fetching user data!",$data);
        }
        catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching user from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        } 
    }

    public function getAll() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $user = new UserManager();

        try {
            $data['admin'] = $user->fetchAllAdmin();
            $data['worker'] = $user->fetchAllWorker();

            $data['admin'] = array_map(function($admin) {
                return array_map('htmlspecialchars', $admin);
            }, $data['admin']);

            $data['worker'] = array_map(function($worker) {
                return array_map('htmlspecialchars', $worker);
            }, $data['worker']);

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching users from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }     
    }
}