<?php

namespace App\Models;

use App\Helper\Utiliti;

class TaskManager 
{
    public function __construct()
    {
    }

    public function fetchAll(): array 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 
                    t.t_id,
                    t.c_id,
                    c.c_name as c_nama,
                    t.t_bike,
                    t.t_plate,
                    t.t_serviceType,
                    t.t_serviceStatus,
                    t.t_totalPrice,
                    t.createdOn,
                    t.isDeleted,
                    f.isPaid 
                FROM garaj_task t
                INNER JOIN garaj_task_finance f ON t.t_id = f.t_id
                INNER JOIN garaj_customer c ON t.c_id = c.c_id
                WHERE t.isDeleted = 0
                ORDER BY t.createdOn DESC;";
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
    
    public function fetchLatest(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT * FROM garaj_task WHERE isDeleted = 0 ORDER BY createdOn DESC LIMIT 5";
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

    public function fetchLatestFiveDaysPaid() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT d.date AS update_date, COALESCE(SUM(f.totalPrice), 0) AS total_profit
                FROM (
                    SELECT CURDATE() - INTERVAL 4 DAY AS date
                    UNION ALL SELECT CURDATE() - INTERVAL 3 DAY
                    UNION ALL SELECT CURDATE() - INTERVAL 2 DAY
                    UNION ALL SELECT CURDATE() - INTERVAL 1 DAY
                    UNION ALL SELECT CURDATE()
                ) d
                LEFT JOIN garaj_task_finance f ON DATE(f.updatedOn) = d.date AND f.isPaid = 1 AND f.isDeleted = 0
                GROUP BY d.date
                ORDER BY d.date ASC";
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

    public function getTaskRevenueDistribution() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 
    			t.t_serviceType, 
    			COALESCE(SUM(f.totalPrice), 0) AS total_revenue 
		FROM garaj_task t
		LEFT JOIN garaj_task_finance f 
    			ON t.t_id = f.t_id
    			AND f.isPaid = 1 
    			AND f.isDeleted = 0
		GROUP BY t.t_serviceType";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalRevenue = 0;
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row; 
            $totalRevenue += $row['total_revenue'];
        }

        $revenuePercentages = [];

        foreach ($data as $row) {
            $serviceType = $row['t_serviceType'];
            $revenue = $row['total_revenue'];

            $percentage = ($totalRevenue > 0) ? ($revenue / $totalRevenue) * 100 : 0;
            $revenuePercentages[$serviceType] = round($percentage, 2); 
        }

        $stmt->close();
        $db->close();

        return $revenuePercentages;
    }

    public function getCustomerPlatformDistribution() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT c_platform, COUNT(*) as total_cx FROM garaj_customer WHERE isDeleted = 0 GROUP BY c_platform";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalCustomers = 0;
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row; 
            $totalCustomers += $row['total_cx'];
        }

        $customerPercentages = [];

        foreach ($data as $row) {
            $platform = $row['c_platform'];
            $customers = $row['total_cx'];

            $percentage = ($totalCustomers > 0) ? ($customers / $totalCustomers) * 100 : 0;
            $customerPercentages[$platform] = round($percentage, 2); 
        }

        $stmt->close();
        $db->close();

        return $customerPercentages;
    }
}