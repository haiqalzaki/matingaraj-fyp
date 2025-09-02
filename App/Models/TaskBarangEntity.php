<?php

namespace App\Models;

use App\Helper\Response;
use App\Helper\Utiliti;
use App\Helper\Logger;

class TaskBarangEntity
{
    private $db;
    private $taskBarangID;
    private $taskID;
    private $barangID = [];
    private $namaBarang = [];
    private $kuantiti = [];
    private $hargaTotalBarang = [];

    public function __construct($db, $id = null) 
    {
        $this->db = $db;

        if ($id !== null) {
            $this->taskID = $id;
            $this->load();
        }
    }

    public function load(): void 
    {
        $sql = "SELECT * FROM garaj_task_item WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->taskID);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            while ($data = $row->fetch_assoc()) {
                $this->taskBarangID[] = $data["titem_id"]; 
                $this->barangID[] = $data["i_id"];
                $this->namaBarang[] = $data["ti_name"];
                $this->kuantiti[] = $data["ti_quantity"];
                $this->hargaTotalBarang[] = $data["ti_priceItemCum"];
            }
        }

        $stmt->close();
    }
 
    public function create(): bool
    {
        $len = count($this->barangID);

        for ($i = 0; $i < $len; $i++) {
            $sql = "INSERT INTO garaj_task_item (
                    t_id,
                    i_id,
                    ti_name,
                    ti_quantity,
                    ti_priceItemCum)
                    VALUES (?,?,?,?,?)";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new \Exception("Error preparing statement for taskBarang insert: {$this->db->error}");
            }

            $stmt->bind_param("iisdd",
                                $this->taskID,
                                $this->barangID[$i],
                                $this->namaBarang[$i],
                                $this->kuantiti[$i],
                                $this->hargaTotalBarang[$i]
                            );                    
        
            if (!$stmt->execute()) {
                throw new \Exception("Error executing taskBarang insert statement! Log: {$this->db->error}");
            }

            $stmt->close();
        } 

        return true;
    }

    public function build($array, $taskID): void
    {
        foreach ($array as $row) {
            $this->taskID = $taskID;
            $this->barangID[] = $row['barang_id'];
            $this->namaBarang[] = $row['barang_name'];
            $this->kuantiti[] = $row['total_quantity'];
            $this->hargaTotalBarang[] = $row['total_price']; 
        }     
    }

    public function save(): bool
    {
        $newBarangArray = $this->getNewBarangData();
        return $this->updateStockAndBarang($newBarangArray);
    }

    public function delete() 
    {
        $sql = "UPDATE garaj_task_item 
                SET isDeleted = 1,  deletedOn = NOW()
                WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task item delete: {$this->db->error}");
        }

        $stmt->bind_param("i", $this->taskID);             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing delete statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    public function updateStockAndBarang($newBarangArray): bool
    {
        // newBarangArray param adalah updated barang

        // existingBarangArray adalah previous barang
        $existingBarangMap = $this->getExistingBarangMap();

        // Loop 1st
        foreach ($newBarangArray as $newBarang) {
            // Semua ni barang baru masuk
            $barangID = $newBarang['barang_id'];
            $namaBarang = $newBarang['barang_name'];
            $newQuantity = $newBarang['total_quantity'];
            $newPrice = $newBarang['total_price'];   

            // Scenario first ada (1,2) kemudian (buang 1 tapi kekal 2), so akan jumpa pada existingMap 2 sahaja dan akan unset
            // Yang tidak unset akan kekal dan pergi pada Loop 2nd

            // Yang akan ada kat sini adalah barang yang baru add sahaja
            // Update stock pada barang sedia ada (barang baru === barang sedia ada (wujud)) barang baru serta masukkan dalam task barang, kemudian unset
            if (isset($existingBarangMap[$barangID])) { // Check ada ke tak barang ni, kalau takde insert, kalau ada jalan proses bawah
                $existingBarang = $existingBarangMap[$barangID]; // Previously mapped barang from DB, masukkan nilai row pada existingBarang
                $previousQuantity = $existingBarang['ti_quantity']; // From database for comparison
            
                $this->updateStock($barangID, $previousQuantity);  // Masukkan previous ke dalam stock dulu (5 + 1 << prev) = 6 < new stock
            
                $this->updateStock($barangID, -$newQuantity); // Baru akan tolak negative value new quantity (6 + (-3)) = 3 < new stock
            
                $this->updateBarang($barangID, $newQuantity, $newPrice);

                // Unset setiap barang yang dah settle
                unset($existingBarangMap[$barangID]);
            } else {
                // Kalau barang dala taskbarang tak wujud maka masukkan
                $this->insertBarang($this->taskID, $barangID, $namaBarang, $newQuantity, $newPrice);
                
                $this->updateStock($barangID, -$newQuantity);
            }
            
        }

        // Loop 2nd
        // Ni maksudnya barangan yang baru add, takde dalam newly add barang, so kena update stock dan delete barang dari taskbarang
        // Yang tinggal kat existingBarangMap adalah barangan yang tidak unset === dalam form baru tiada barang tersebut === delete dari taskbarang dan update stock
        foreach ($existingBarangMap as $barangID => $existingBarang) {
            $this->updateStock($barangID, $existingBarang['ti_quantity']);

            $this->deleteBarang($barangID);
        }
        
        $this->loadBarangData($this->taskID);

        return true;
    }

    private function getExistingBarangMap(): array
    {
        $existingBarangMap = [];
        $stmt = $this->db->prepare("SELECT i_id, ti_name, ti_quantity, ti_priceItemCum FROM garaj_task_item WHERE t_id = ?");
        $stmt->bind_param("i", $this->taskID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $existingBarangMap[$row['i_id']] = $row;  // guna id barang as key for each array (note, id barang is still in array, just used as key for each array)
        }

        $stmt->close();

        return $existingBarangMap;
    }

    private function getNewBarangData(): array
    {
        $newBarangArray = [];

        for ($i = 0; $i < count($this->barangID); $i++) {
            $newBarangArray[] = [
                'barang_id' => $this->barangID[$i],
                'barang_name' => $this->namaBarang[$i],
                'total_quantity' => $this->kuantiti[$i],
                'total_price' => $this->hargaTotalBarang[$i],
            ];
        }

        return $newBarangArray;
    }

    private function updateBarang($barangID, $newQuantity, $newPrice)
    {
        $sql = "UPDATE garaj_task_item SET ti_quantity = ?, ti_priceItemCum = ? WHERE i_id = ? AND t_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ddii", $newQuantity, $newPrice, $barangID, $this->taskID);
        $stmt->execute();
        $stmt->close();
    }

    private function insertBarang($taskID, $barangID, $namaBarang, $quantity, $price)
    {
        $sql = "INSERT INTO garaj_task_item (t_id, i_id, ti_name, ti_quantity, ti_priceItemCum) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iisdd", $taskID, $barangID, $namaBarang, $quantity, $price);
        $stmt->execute();
        $stmt->close();
    }

    private function deleteBarang($barangID)
    {
        $sql = "DELETE FROM garaj_task_item WHERE i_id = ? AND t_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $barangID, $this->taskID);
        $stmt->execute();
        $stmt->close();
    }

    private function updateStock($barangID, $quantityChange)
    {
        $sql = "SELECT i_stock, i_name FROM garaj_inventory WHERE i_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $barangID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $currentStock = $row['i_stock'];
            $barangName = $row['i_name'];
        } else {
            $stmt->close();
            throw new \Exception("Stock data not found for inventory id: $barangID");
        }
        $stmt->close();

        $newStock = $currentStock + $quantityChange;

        if ($newStock < 0) {
            Response::returnJSON(false, "Insufficient stock for item: $barangName. \nCurrent stock: $currentStock");
            exit();
        }

        $sql = "UPDATE garaj_inventory SET i_stock = ? WHERE i_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $newStock, $barangID);
        $stmt->execute();
        $stmt->close();
    }

    private function loadBarangData($taskID) {
        $this->barangID = [];
        $this->namaBarang = [];
        $this->kuantiti = [];
        $this->hargaTotalBarang = [];

        $stmt = $this->db->prepare("SELECT i_id, ti_name, ti_quantity, ti_priceItemCum FROM garaj_task_item WHERE t_id = ?");
        $stmt->bind_param("i", $taskID);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $this->barangID[] = $row['i_id'];
            $this->namaBarang[] = $row['ti_name'];
            $this->kuantiti[] = $row['ti_quantity'];
            $this->hargaTotalBarang[] = $row['ti_priceItemCum'];
        }

        $stmt->close();
    }

    public function updateStockDeleted(): bool
    {
        $existingBarangMap = $this->getExistingBarangMap();

        foreach ($existingBarangMap as $barang) {
            $barangID = $barang['i_id'];
            $barangQuantity = $barang['ti_quantity'];
            
            $this->updateStock($barangID, $barangQuantity);  // Masukkan previous ke dalam stock dulu (5 + 1 << prev) = 6 < new stock   
        }

        return true;
    }

    public function setTaskBarangID($taskBarangID) { $this->taskBarangID = $taskBarangID; } 
    public function getTaskBarangID() { return $this->taskBarangID; }
    
    public function setTaskID($taskID) { $this->taskID = $taskID; } 
    public function getTaskID() { return $this->taskID; }
    
    public function setBarangID($barangID) { $this->barangID = $barangID; } 
    public function getBarangID() { return $this->barangID; }
    
    public function setNamaBarang($namaBarang) { $this->namaBarang = $namaBarang; } 
    public function getNamaBarang() { return $this->namaBarang; }
    
    public function setKuantiti($kuantiti) { $this->kuantiti = $kuantiti; } 
    public function getKuantiti() { return $this->kuantiti; }
    
    public function setHargaTotalBarang($hargaTotalBarang) { $this->hargaTotalBarang = $hargaTotalBarang; } 
    public function getHargaTotalBarang() { return $this->hargaTotalBarang; } 
}