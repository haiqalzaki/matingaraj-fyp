<?php

namespace App\Models;

class TaskFinanceEntity
{
    private $db;
    private $taskFinanceID;
    private $taskID;
    private $taskHargaTotal;
    private $dibayar_pada;
    private $taskPaidBool;

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
        $sql = "SELECT * FROM garaj_task_finance WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->taskID);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            $data = $row->fetch_assoc();

            $this->taskFinanceID = $data['tfin_id'];
            $this->taskHargaTotal = $data['totalPrice'];
            $this->taskPaidBool = $data['isPaid'];
            $this->dibayar_pada = $data['updatedOn'];   
        }

        $stmt->close();
    }
 
    public function create(): bool
    {
        $sql = "INSERT INTO garaj_task_finance (
                t_id,
                totalPrice,
                isPaid)
                VALUES (?,?,?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task Finance insert: {$this->db->error}");
        }

        $stmt->bind_param("idi",
                            $this->taskID,
                            $this->taskHargaTotal,
                            $this->taskPaidBool
                        );                    
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing task Finance insert statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    public function save(): bool
    {
        $sql = "UPDATE garaj_task_finance
                SET 
                    totalPrice = ?, 
                    isPaid = ?
                WHERE tfin_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task update: {$this->db->error}");
        }

        $stmt->bind_param("dii", 
                        $this->taskHargaTotal, 
                        $this->taskPaidBool,
                        $this->taskFinanceID
                    );             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing update statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    public function build($taskID, $taskHargaTotal, $taskPaidBool): void
    {
        $this->taskID = $taskID;
        $this->taskHargaTotal = $taskHargaTotal;
        $this->taskPaidBool = $taskPaidBool !== "Paid" ? 0 : 1;
    }

    public function delete() 
    {
        $sql = "UPDATE garaj_task_finance
                SET isDeleted = 1,  deletedOn = NOW()
                WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task finance delete: {$this->db->error}");
        }

        $stmt->bind_param("i", $this->taskID);             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing delete statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    public function setTaskFinanceID($taskFinanceID) { $this->taskFinanceID = $taskFinanceID; } 
    public function getTaskFinanceID() { return $this->taskFinanceID; }

    public function setTaskID($taskID) { $this->taskID = $taskID; } 
    public function getTaskID() { return $this->taskID; }

    public function setTaskHargaTotal($taskHargaTotal) { $this->taskHargaTotal = $taskHargaTotal; } 
    public function getTaskHargaTotal() { return $this->taskHargaTotal; }

    public function setTaskPaidBool($taskPaidBool) { $this->taskPaidBool = $taskPaidBool; } 
    public function getTaskPaidBool() { return $this->taskPaidBool; }

    public function getDibayarPada() { return $this->dibayar_pada; }
}