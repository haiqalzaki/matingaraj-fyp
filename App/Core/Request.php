<?php

namespace App\Core;

use App\Helper\Response;
use App\Helper\Logger;
use App\Helper\Utiliti;

class Request
{
    protected $requestedController;
    protected $requestedAction;
    protected $allowedMethod = ['POST','GET'];
    protected $requestMethod;

    protected $getData;
    protected $postData;
    protected $postFile;

    protected $response;

    public function __construct(object $controller, string $action, array $params = []) 
    {
        $this->requestedController = $controller;
        $this->requestedAction = $action;
        $this->getData = $params;
        $this->postData = $_POST;
        $this->postFile = $_FILES;

        $this->requestMethod = $_SERVER['REQUEST_METHOD'];

        if (!in_array($this->requestMethod, $this->allowedMethod)) {
            http_response_code(405);
            Logger::log("Request Layer Error: Method ". $this->requestMethod ." is not allowed.");
            exit();
        }

        if ($this->isPostRequest()) 
        {
            // Periksa setiap nilai element dalam array POST, dan unset jika kosong
            foreach ($this->postData as $key => $value) {
                if ($value === '') {
                    unset($this->postData[$key]);
                }
            }

            // Periksa form POST, jika kosong hentikan permintaan dan pulangkan respon
            if (empty($this->postData)) { 
                Logger::log("Request Layer Error: POST field is empty.");
                Response::returnJSON(false, "Invalid request!");      
            }

            // Jika fail wujud di dalam POST, hantar fail juga!
            if (isset($this->postFile) && !empty($this->postFile)) {
                $this->response = call_user_func_array([$this->requestedController, $this->requestedAction], [$this->postData, $this->postFile]);
            } else {
                $this->response = call_user_func_array([$this->requestedController, $this->requestedAction], [$this->postData]);
            }
        } else {
            $this->response = call_user_func_array([$this->requestedController, $this->requestedAction], [$this->getData]);
        }
    }

    private function isPostRequest(): bool 
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}