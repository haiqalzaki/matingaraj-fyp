<?php

namespace App\Helper;

class Response 
{
    public static function returnJSON(bool $status, string $message = null, array $data = []) 
    {
        header("Content-type: application/json");
        echo json_encode([
            "status"=> $status,
            "message"=> $message, 
            "data" => $data
        ]);
        http_response_code(200);
        exit();
    }
}


