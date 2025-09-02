<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Response;
use App\Models\CustomerManager;
use App\Models\TaskManager;
use App\Models\TaskDetailEntity;

class Dashboard extends Controller 
{
    private $tajuk = 'Dashboard';
    private $css = 'dashboard';
    
    public function index() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $cx = new CustomerManager();

        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        $data['customer'] = $cx->fetchLatest();
        
       
        $this->view('Partials/head', $data);   
        $this->view('Partials/navigation');

        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/Dashboard/index', $data);
        $this->view('Partials/close-wrapper');

        $this->view('Partials/footer');
        exit();
    }

    public function cxTable() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $cx = new CustomerManager();
        
        $data = $cx->fetchLatest();

        Response::returnJSON(true, null, $data);
    }

    public function taskTable() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $task = new TaskManager();

        $data = $task->fetchLatest();

        Response::returnJSON(true, null, $data);

    }

    public function paparkanStatus()
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    
        $task = new TaskDetailEntity($db);
        $status = $task->dapatkanStatus();

        $db->close();

        Response::returnJSON(true,null,$status);  
    }

    public function taskChart() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
        
        $task = new TaskManager();

        $data['latestPaid'] = $task->fetchLatestFiveDaysPaid();
        $data['revenueByService'] = $task->getTaskRevenueDistribution();
        $data['customerByPlatform'] = $task->getCustomerPlatformDistribution();

        Response::returnJSON(true, null, $data);
    }
}

