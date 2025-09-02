<?php

namespace App\Models;

class BarangValidator 
{
    public static $lastBarang = [];

    public static function checkBarangStockAndUpdate($db, $array) 
    {
        self::$lastBarang = []; 

        foreach ($array as $barang) {
            $barangID = $barang['barang_id'];
            $barangName = $barang['barang_name'];
            $requestedQuantity = $barang['total_quantity'];

            // If quantity is 0, not valid
            if ($requestedQuantity < 1) {
                self::$lastBarang[] = "Kuantiti tidak valid bagi barang: $barangName";
                return false;
            }
            
            // Get each barang to check if exist and if stock sufficient or not
            // Why For UPDATE is present? To lock the rows, to prevent any operations being done, to maintain data consistency
            $sql = "SELECT i_id, i_name, i_stock
                    FROM garaj_inventory 
                    WHERE i_id = ?
                    FOR UPDATE";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("i", $barangID);
            $stmt->execute();
            $result = $stmt->get_result();

            $barangData = $result->fetch_assoc();
            
            // If barang doesn't exist
            if (!$barangData) {
                self::$lastBarang[] = "Barang $barangName does not exist.";
                return false;
            }

            // If stock is not sufficient, not valid
            if ($barangData['i_stock'] < $requestedQuantity) {
                self::$lastBarang[] = $barangData['i_name'] . " (Stock insufficient)";
                return false;
            }

            // Update barang stock quantity for each barang
            $stockBaru = $barangData['i_stock'] - $requestedQuantity;

            $updateSql = "UPDATE garaj_inventory 
                        SET i_stock = ? 
                        WHERE i_id = ?";
            $updateStmt = $db->prepare($updateSql);
            $updateStmt->bind_param("ii", $stockBaru, $barangID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows <= 0) {
                self::$lastBarang[] = "Failed updating stock: $barangName.";
                return false;
            }

            // Check barang stock integrity, if not consistent then return false > rollback
            $checkStockSql = "SELECT i_stock 
                            FROM garaj_inventory 
                            WHERE i_id = ?";
            $checkStmt = $db->prepare($checkStockSql);
            $checkStmt->bind_param("i", $barangID);
            $checkStmt->execute();
            $resultCheck = $checkStmt->get_result();
            $newStockData = $resultCheck->fetch_assoc();

            if ($newStockData['i_stock'] !== $stockBaru) {
                self::$lastBarang[] = "Stock inconsistent for: $barangName.";
                return false;
            }

            // Close all statements for each barang
            $stmt->close(); 
            $updateStmt->close();
            $checkStmt->close();  
        }
        return true;
    }
}