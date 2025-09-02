<?php

namespace App\Models;

class TaskDetailEntity
{
    private $db;
    private $cxID;
    private $taskID;
    private $motor;
    private $motorSearch;
    private $plate;
    private $plateSearch;
    private $servis;
    private $status;
    private $remark;
    private $markupTask;
    private $barangTotal;
    private $taskTotal;
    private $dicipta_pada;
    private $dikemaskini_pada;
    private $lastInsertID;

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
        $sql = "SELECT * FROM garaj_task WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $this->taskID);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            $data = $row->fetch_assoc();

            $this->taskID = $data["t_id"];
            $this->cxID = $data["c_id"];
            $this->motor = $data["t_bike"];
            $this->motorSearch = $data["t_bike_search"];
            $this->plate = $data["t_plate"];
            $this->plateSearch = $data["t_plate_search"];
            $this->servis = $data["t_serviceType"];
            $this->status = $data["t_serviceStatus"];
            $this->remark = $data["t_remark"];
            $this->markupTask = $data["t_markupTotal"];
            $this->barangTotal = $data["t_itemTotal"];
            $this->taskTotal = $data["t_totalPrice"];
            $this->dicipta_pada = $data["createdOn"];
            $this->dikemaskini_pada = $data['updatedOn'];
        }

        $stmt->close();
    }
 
    public function create(): bool
    {
        $sql = "INSERT INTO garaj_task (
                c_id,
                t_bike,
                t_bike_search,
                t_plate,
                t_plate_search,
                t_serviceType,
                t_serviceStatus,
                t_remark,
                t_markupTotal,
                t_itemTotal,
                t_totalPrice)
                VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task insert: {$this->db->error}");
        }

        $stmt->bind_param("isssssssddd",
                            $this->cxID,
                            $this->motor,
                            $this->motorSearch,
                            $this->plate,
                            $this->plateSearch,
                            $this->servis,
                            $this->status,
                            $this->remark,
                            $this->markupTask,
                            $this->barangTotal,
                            $this->taskTotal
                        );                    
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing insert statement! Log: {$this->db->error}");
        }

        $this->lastInsertID = $this->db->insert_id;

        $stmt->close();

        return true;
    }

    public function save(): bool
    {
        $sql = "UPDATE garaj_task
                SET 
                    t_bike = ?, 
                    t_bike_search = ?, 
                    t_plate = ?, 
                    t_plate_search = ?, 
                    t_serviceStatus = ?, 
                    t_serviceType = ?, 
                    t_remark = ?,
                    t_markupTotal = ?, 
                    t_itemTotal = ?, 
                    t_totalPrice = ? 
                WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task update: {$this->db->error}");
        }

        $stmt->bind_param("sssssssdddi", 
                        $this->motor, 
                        $this->motorSearch, 
                        $this->plate, 
                        $this->plateSearch, 
                        $this->status, 
                        $this->servis, 
                        $this->remark,
                        $this->markupTask,
                        $this->barangTotal,
                        $this->taskTotal,
                        $this->taskID
                    );             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing update statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    public function delete() 
    {
        $sql = "UPDATE garaj_task 
                SET isDeleted = 1, deletedOn = NOW()
                WHERE t_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for task delete: {$this->db->error}");
        }

        $stmt->bind_param("i", $this->taskID);             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing delete statement! Log: {$this->db->error}");
        }

        $stmt->close();

        return true;
    }

    
    public function build($array): void
    {
        $this->cxID = $array['cxID'];
        $this->motor = $array['motor'];
        $this->motorSearch = $array['motorSearch'];
        $this->plate = $array['plate'];
        $this->plateSearch = $array['plateSearch'];
        $this->servis = $array['servis'];
        $this->status = $array['status'];
        $this->remark = $array['remark'];
        $this->markupTask = $array['markup'];
        $this->barangTotal = $array['barangTotal'];
        $this->taskTotal = $array['taskTotal'];
    }

    public function dapatkanstatus() {
        $sql = "SELECT t_serviceStatus, COUNT(*) AS jumlah FROM garaj_task WHERE isDeleted = 0 GROUP BY t_serviceStatus";
        $result = $this->db->query($sql);
    
        if (!$result) {
            die("SQL Error: " . $this->db->error);
        }
    
        $statusCount = [
            'pending' => 0,
            'ongoing' => 0,
            'completed' => 0,
            'paid' => 0
        ];
    
        while ($row = $result->fetch_assoc()) {
            $status = strtolower($row['t_serviceStatus']);
            
            if (array_key_exists($status, $statusCount)) {
                $statusCount[$status] = (int) $row['jumlah'];
            }
        }

        $sql = "SELECT COUNT(*) as total_paid FROM garaj_task_finance WHERE isPaid = 1 AND isDeleted = 0";
        $result = $this->db->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            $statusCount['paid'] = (int) $row['total_paid'];
        }
    
        return $statusCount;
    }

    public function setCxID($cxID) { $this->cxID = $cxID; } 
    public function getCxID() { return $this->cxID; }

    public function setTaskID($taskID) { $this->taskID = $taskID; } 
    public function getTaskID() { return $this->taskID; }

    public function setMotor($motor) { $this->motor = $motor; } 
    public function getMotor() { return $this->motor; }

    public function setMotorSearch($motorSearch) { $this->motorSearch = $motorSearch; } 
    public function getMotorSearch() { return $this->motorSearch; }

    public function setPlate($plate) { $this->plate = $plate; } 
    public function getPlate() { return $this->plate; }
    
    public function setPlateSearch($plateSearch) { $this->plateSearch = $plateSearch; } 
    public function getPlateSearch() { return $this->plateSearch; }

    public function setServis($servis) { $this->servis = $servis; } 
    public function getServis() { return $this->servis; }
    
    public function setStatus($status) { $this->status = $status; } 
    public function getStatus() { return $this->status; }

    public function setRemark($remark) { $this->remark = $remark; } 
    public function getRemark() { return $this->remark; }

    public function setMarkupTask($markupTask) { $this->markupTask = $markupTask; } 
    public function getMarkupTask() { return $this->markupTask; }

    public function setBarangTotal($barangTotal) { $this->barangTotal = $barangTotal; } 
    public function getBarangTotal() { return $this->barangTotal; }

    public function setTaskTotal($taskTotal) { $this->taskTotal = $taskTotal; } 
    public function getTaskTotal() { return $this->taskTotal; }

    public function getDiciptaPada() { return $this->dicipta_pada; }
    public function getDikemaskiniPada() {return $this->dikemaskini_pada; }

    public function setLastInsertID($lastInsertID) { $this->lastInsertID = $lastInsertID; } 
    public function getLastInsertID() { return $this->lastInsertID; }

}