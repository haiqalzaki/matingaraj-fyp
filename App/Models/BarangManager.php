<?php

namespace App\Models;

class BarangManager 
{
    public function __construct()
    {
    }

    public function fetchAll(): array
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT i_id,i_image_path,i_name,i_stock,i_cost,i_markup,i_totalPrice,i_remark,createdOn 
                FROM garaj_inventory 
                WHERE isDeleted = 0 
                ORDER BY createdOn DESC";
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

    public function fetchBarangAvailable() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        // fetch available row in database (equates to each barang row excluding yang dihapus)
        $sql = "SELECT count(*) as total_barang FROM garaj_inventory WHERE isDeleted = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $stmt->close();
        $db->close();

        // return result as integer
        return $row;
    }

    public function fetchInStockTotal() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT COUNT(*) AS total_instock 
                FROM garaj_inventory 
                WHERE i_stock > 0 AND isDeleted = 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        $stmt->close();
        $db->close();

        return $row;
    }

    public function mostUsedBarang() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT ti_name, SUM(ti_quantity) AS total_quantity FROM garaj_task_item WHERE isDeleted = 0 GROUP BY ti_name ORDER BY total_quantity DESC LIMIT 5";
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