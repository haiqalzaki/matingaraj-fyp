<?php

namespace App\Models;

class StatisticManager 
{
    public function __construct()
    {
    }

    public function mostUsedBarang() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT ti_name, SUM(ti_quantity) AS total_quantity FROM garaj_task_item WHERE isDeleted = 0 GROUP BY ti_name ORDER BY total_quantity DESC LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function mostCustomerByPlatform() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT c_platform, COUNT(*) AS total FROM garaj_customer WHERE isDeleted = 0 GROUP BY c_platform ORDER BY total DESC LIMIT 10";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function mostUsedMotorcycle() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT t_bike, COUNT(*) AS total FROM garaj_task WHERE isDeleted = 0 GROUP BY t_bike ORDER BY total DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function getStatusCount() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT t_serviceStatus, COUNT(*) AS total FROM garaj_task WHERE isDeleted = 0 GROUP BY t_serviceStatus ORDER BY total DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function getServiceTypeCount() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        
        $sql = "SELECT t_serviceType, COUNT(*) AS total FROM garaj_task WHERE isDeleted = 0 GROUP BY t_serviceType ORDER BY total DESC;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function getPaidStatusCount() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        
        $sql = "SELECT isPaid, COUNT(*) AS total FROM garaj_task_finance WHERE isDeleted = 0 GROUP BY isPaid ORDER BY total;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }

    public function getTotalUsers() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        
        $sql = "SELECT u_isAdmin, COUNT(*) AS total FROM garaj_user WHERE isDeleted = 0 GROUP BY u_isAdmin ORDER BY total;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $arrays = [];

        while ($row = $result->fetch_assoc()) {
            $arrays[] = $row;
        }

        $stmt->close();
        $db->close();

        return $arrays;
    }
}