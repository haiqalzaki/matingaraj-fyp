<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Response;
use App\Models\StatisticManager;

class Statistic extends Controller 
{
    private $tajuk = 'Statistic';
    private $css = 'statistic';

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
        $this->view('Templates/Statistic/index', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit();
    }

    public function fetch() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
        
        $task = new StatisticManager();

        $data['usedBarang'] = $task->mostUsedBarang();
        $data['usedMotorcycle'] = $task->mostUsedMotorcycle();
        $data['customerByPlatform'] = $task->mostCustomerByPlatform();
        $data['statusCount'] = $task->getStatusCount();
        $data['serviceCount'] = $task->getServiceTypeCount();
        $data['paidStatusCount'] = $task->getPaidStatusCount();
	    $data['totalUsers'] = $task->getTotalUsers();

        Response::returnJSON(true, null, $data);
    }
}