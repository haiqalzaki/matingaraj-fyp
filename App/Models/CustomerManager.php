<?php

namespace App\Models;

class CustomerManager 
{
    public function __construct()
    {
    }

    public function fetchAll(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT c_id,c_name,c_name_search,c_email,c_phone,c_platform,createdOn 
                FROM garaj_customer
                WHERE isDeleted = 0
                ORDER BY createdOn DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }
        return $arrays;
    }

    public function fetchLatest(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT c_id,c_name,c_email,c_phone,c_platform,createdOn FROM garaj_customer WHERE isDeleted = 0 ORDER BY createdOn DESC LIMIT 5";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $c_id = $row['c_id'];
    
            $sql = "SELECT COUNT(*) AS total_task FROM garaj_task WHERE c_id = ? AND isDeleted = 0";
            $countStmt = $db->prepare($sql);
            $countStmt->bind_param("i", $c_id);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $countRow = $countResult->fetch_assoc();
    
            $countStmt->close();
    
            $row['c_total_task'] = $countRow['total_task'] ?? 0;
            $arrays[] = $row;
        }
    
        $stmt->close();
        $db->close();
    
        return $arrays;
    }

    public function totalAll() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT COUNT(*) as totalRows FROM garaj_customer WHERE isDeleted = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $db->close();

        return $result['totalRows'];
    }

    public function latestDashboard($offset, $maxRows) 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT c_id,c_name,c_email,c_phone,c_platform,dicipta_pada FROM garaj_customer WHERE isDeleted = 0 ORDER BY createdOn DESC LIMIT ?, ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $offset, $maxRows);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $db->close();

        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $row['latest_task_id'] = $this->latestTaskID($row['c_id']); 
            $arrays[] = $row;
        }

        return $arrays;
    }

    private function latestTaskID($cxID) 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT t_id 
                FROM garaj_task 
                WHERE c_id = ? 
                ORDER BY createdOn DESC 
                LIMIT 1";

        $stmt = $db->prepare($sql);
        $stmt->bind_param('i', $cxID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $db->close();

        return $result['t_id'] ?? null;
    }

    public function fetchTotalByPlatform(): array 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $returnArray = [];

        $array = ['Whatsapp', 'Facebook', 'Instagram', 'TikTok'];

        foreach ($array as $value) {
            $column = ucfirst($value);
            $sql = "SELECT COUNT(*) as $column FROM garaj_customer WHERE c_platform = '$value' AND isDeleted = 0";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $returnArray[$column] = $row[$column];
        }

        $stmt->close();
        $db->close();

        return $returnArray;  
    }
}