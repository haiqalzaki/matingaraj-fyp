<?php

namespace App\Models;

class UserManager 
{
    public function __construct()
    {
    }

    public function fetchAllAdmin(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT u_id,u_name,u_email,u_phone,u_isAdmin,isDeleted FROM garaj_user WHERE isDeleted = 0 AND u_isAdmin = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $db->close();

        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }
        return $arrays;
    }
    
    public function fetchAllWorker(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT u_id,u_name,u_email,u_phone,u_isAdmin,isDeleted FROM garaj_user WHERE isDeleted = 0 AND u_isAdmin = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $db->close();

        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }
        return $arrays;
    }
}