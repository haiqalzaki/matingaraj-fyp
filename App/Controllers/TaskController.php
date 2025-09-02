<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Response;
use App\Helper\Logger;
use App\Models\TaskFacade;
use App\Models\TaskManager;

class Task extends Controller 
{
    private $tajuk = 'Task';
    private $css = 'task';

    public function index() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/Task/index', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit();
    }

    public function handler() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = 'handler';
        
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Templates/Task/handler', $data);
        $this->view('Partials/footer');
        exit();
    }

    public function fetch($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = 'fetch';

        $task = new TaskFacade($params[0]);
        $data['task'] = $task->fetch();

        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Templates/Task/fetch', $data);
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

        $taskBarang = $params['barang'] ?? null;

        if ($taskBarang === null) {
            Response::returnJSON(false, 'Barang cannot be empty!');
            exit();
        }

        $taskDetails = [
            "cxID" => isset($params['customerID']) ? (int) $params['customerID'] : null,
            "motor" => $params['motor'] ?? null,
            "motorSearch" => strtolower(str_replace(' ','', $params['motor'] ?? '')),
            "plate" => $params['plate'] ?? null,
            "plateSearch" => strtolower(str_replace(' ','', $params['plate'] ?? '')),
            "servis" => $params['servis'] ?? null,
            "status" => $params['status'] ?? null,
            "remark" => $params['remark'] ?? "",
            "markup" => isset($params['markup']) ? (float) $params['markup'] : 0.00,
            "barangTotal" => 0.00,
            "taskTotal" => 0.00
        ];

        foreach ($taskDetails as $key => $value) {
            if ($key === 'cxID') {
                if (!isset($value) || $value === null || !is_int($value)) {
                    Response::returnJSON(false, 'Customer cannot be empty!', $taskDetails);
                    exit();
                }
            }
            if ($key === 'motor' || $key === 'plate' || $key === 'servis' || $key === 'status' ) {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    exit();
                }
                if ($value === '') {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty field!');
                    exit();
                } 
            }   
            if ($key === 'remark') {
                if (strlen($value) > 500) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be more than 500 characters!');
                    exit();
                }
            }
            if ($key === 'plate') {
                if (!preg_match('/^(?:[A-Z]{1,3}|MALAYSIA|PUTRAJAYA|SUKOM|PATRIOT|1M4U|G1M)\s?\d{1,4}\s?[A-Z]?$/i', $value) || (strlen($value) > 15)) {
                    Response::returnJSON(false, ucfirst($key) . ' is not valid plate number!');
                    exit();
                }
            }
        }

        $taskDetails['plate'] = strtoupper($taskDetails['plate']);

        foreach ($taskBarang as $row) {
            $taskDetails['barangTotal'] += $row['total_price'];
        }

        $taskDetails['taskTotal'] = $taskDetails['barangTotal'] + $taskDetails['markup'];

        try {
            $task = new TaskFacade();

            if (!$task->create($taskDetails, $taskBarang)) {
                Response::returnJSON(false, 'Creating task failed! Try again.');
                Logger::log('Task creation failed on facade!');
                die();
            }

            Response::returnJSON(true, "Successfully added task!");
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed adding task!');
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

        $taskBarang = $params['barang'] ?? null;

        if ($taskBarang === null) {
            Response::returnJSON(false, 'Barang cannot be empty!');
            exit();
        }

        $taskDetails = [
            "taskID" => isset($params['taskID']) ? (int) $params['taskID'] : null,
            "motor" => $params['motor'] ?? null,
            "motorSearch" => strtolower(str_replace(' ','', $params['motor'] ?? '')),
            "plate" => $params['plate'] ?? null,
            "plateSearch" => strtolower(str_replace(' ','', $params['plate'] ?? '')),
            "servis" => $params['servis'] ?? null,
            "status" => $params['status'] ?? null,
            "remark" => $params['remark'] ?? "",
            "markup" => isset($params['markup']) ? (float) $params['markup'] : 0.00,
            "barangTotal" => 0.00,
        ];

        $taskFinance = [
            "financeID" => isset($params['financeID']) ? (int) $params['financeID'] : null,
            "isPaid" => isset($params['isPaid']) ? (int) $params['isPaid'] : 0
        ];

        foreach ($taskDetails as $key => $value) {
            if ($key === 'taskID') {
                if (!isset($value) || $value === null || !is_int($value)) {
                    Response::returnJSON(false, 'Task ID cannot be empty!', $taskDetails);
                    exit();
                }
            }
            if ($key === 'motor' || $key === 'plate' || $key === 'servis' || $key === 'status' ) {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    exit();
                }
                if ($value === '') {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty field!');
                    exit();
                } 
            }   
            if ($key === 'remark') {
                if (strlen($value) > 500) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be more than 500 characters!');
                    exit();
                }
            }
            if ($key === 'plate') {
                if (!preg_match('/^(?:[A-Z]{1,3}|PUTRAJAYA|SUKOM|PATRIOT|1M4U|G1M)\s?\d{1,4}\s?[A-Z]?$/i', $value) || (strlen($value) > 15)) {
                    Response::returnJSON(false, ucfirst($key) . ' is not valid plate number!');
                    exit();
                }
            }
        }

        $taskDetails['plate'] = strtoupper($taskDetails['plate']);

        foreach ($taskBarang as $row) {
            $taskDetails['barangTotal'] += $row['total_price'];
        }

        $taskDetails['taskTotal'] = $taskDetails['barangTotal'] + $taskDetails['markup'];
        
        try {
            $taskUpdate = new TaskFacade($taskDetails['taskID'], $taskFinance['financeID']);

            if (!$taskUpdate->save($taskDetails, $taskBarang, $taskFinance)) {
                Response::returnJSON(false, 'Updating task failed! Try again.');
                Logger::log('Task update failed on facade!');
                die();
            }

            Response::returnJSON(true, "Successfully updated task!", [$taskDetails, $taskBarang, $taskFinance]);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed updating task!');
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

        $id = (int) $params['taskID'] ?? null;

        try {
            $taskDelete = new TaskFacade($id, null);

            if (!$taskDelete->delete()) {
                Response::returnJSON(false, 'Deleting task failed! Try again.');
                Logger::log('Task update failed on facade!');
                die();
            }

            Response::returnJSON(true, "Successfully deleted task!");
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed deleted task!');
            Logger::log('Exception caught: '. $e->getMessage());
        }  
    }

    public function getTask($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
        // Instantiate task by ID
        $task = new TaskFacade($params[0]);

        // Fetch task details along with associated customer
        $data = $task->fetch();

        Response::returnJSON(true, null, $data);
    }

    public function getAll() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $task = new TaskManager();

        try {
            $data = $task->fetchAll();

            $data = array_map(function($task) {
                return array_map('htmlspecialchars', $task);
            }, $data);

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching task data from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }     
    }
}