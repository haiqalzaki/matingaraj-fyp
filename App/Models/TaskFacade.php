<?php

namespace App\Models;

use App\Helper\Logger;
use App\Helper\Response;
use App\Helper\Utiliti;

class TaskFacade
{
    private $db;
    private $taskID;
    public $task;
    public $barang;
    public $finance;

    public function __construct($id = null, $financeID = null) 
    {
        $this->db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        if ($id !== null) {
            $this->taskID = $id;
            $this->task = new TaskDetailEntity($this->db, $id);
            $this->barang = new TaskBarangEntity($this->db, $id);
            $this->finance = new TaskFinanceEntity($this->db, $id);
        }
    }

    public function create($taskDetail, $taskBarang) 
    {
        try {
            $this->db->begin_transaction();

            if (!BarangValidator::checkBarangStockAndUpdate($this->db, $taskBarang)) {
                $this->db->rollback();
                Response::returnJSON(false, 'Barang: ' . BarangValidator::$lastBarang[0]);
                Logger::log('Stock insufficient from BarangValidator method!');
                die();
            }

            $this->task = new TaskDetailEntity($this->db);
            $this->task->build($taskDetail);
            $this->task->create();
    
            $this->barang = new TaskBarangEntity($this->db);
            $this->barang->build($taskBarang, $this->task->getLastInsertID());
            $this->barang->create();
    
            $this->finance = new TaskFinanceEntity($this->db);
            $this->finance->build(
                                    $this->task->getLastInsertID(),
                                    $this->task->getTaskTotal(),
                                    $this->task->getStatus()
                                );
            $this->finance->create();

            $this->db->commit();

            Logger::logSuccess("Task is successfully created by: " . $_SESSION['username'] . " | Task ID: " . $this->task->getLastInsertID());

            return true;
        } catch (\Exception $e) {
            Logger::log("Error on Task Facade: " . $e->getMessage());
            $this->db->rollback();
            return false;
        } finally {
            $this->db->close();
        }
    }

    public function save($taskDetail, $taskBarang, $taskFinance) 
    {
        try {
            $this->db->begin_transaction();

            $this->task->setMotor($taskDetail['motor']);
            $this->task->setMotorSearch($taskDetail['motorSearch']);
            $this->task->setPlate($taskDetail['plate']);
            $this->task->setPlateSearch($taskDetail['plateSearch']);
            $this->task->setServis($taskDetail['servis']);
            $this->task->setStatus($taskDetail['status']);
            $this->task->setRemark($taskDetail['remark']);
            $this->task->setMarkupTask($taskDetail['markup']);
            $this->task->setBarangTotal($taskDetail['barangTotal']);
            $this->task->setTaskTotal($taskDetail['taskTotal']);

            $this->finance->setTaskHargaTotal($taskDetail['taskTotal']);
            $this->finance->setTaskPaidBool($taskFinance['isPaid']);

            $newBarangArray = $this->prepareBarangArray($taskBarang);
            $this->barang->updateStockAndBarang($newBarangArray); 

            $this->task->save();
            $this->finance->save();

            $this->db->commit();

            Logger::logSuccess("Task is successfully updated by: " . $_SESSION['username'] . " | Task ID: " . $this->task->getTaskID());

            return true;
        } catch (\Exception $e) {
            Logger::log("Error on Task Facade: " . $e->getMessage());
            $this->db->rollback();
            return false;
        } finally {
            $this->db->close();
        }
    }

    public function delete() 
    {
        try {
            $this->db->begin_transaction();

            $this->barang->updateStockDeleted();
            $this->barang->delete();
            $this->finance->delete();
            $this->task->delete();

            $this->db->commit();

            Logger::logSuccess("Task is successfully deleted by: " . $_SESSION['username'] . " | Task ID: " . $this->task->getTaskID());

            return true;
        } catch (\Exception $e) {
            Logger::log("Error on Task Facade on Delete: " . $e->getMessage());
            $this->db->rollback();
            return false;
        } finally {
            $this->db->close();
        }
    }

    public function fetch() 
    {
        $cxObject = new CustomerEntity($this->task->getCxID());

        $array['customer'] = [
            'id'=> $cxObject->getId(),
            'name'=> $cxObject->getName(),
            'telefon'=> $cxObject->getPhone(),
            'email'=> $cxObject->getEmail(),
            'platform'=> $cxObject->getPlatform()
        ];

        $array['detail'] = [
            'task_id'=> $this->task->getTaskID(),
            'motor'=> $this->task->getMotor(),
            'plate'=> $this->task->getPlate(),
            'servis'=> $this->task->getServis(),
            'status'=> $this->task->getStatus(),
            'remark'=> $this->task->getRemark(),
            'markup_total'=> $this->task->getMarkupTask(),
            'barang_total'=> $this->task->getBarangTotal(),
            'dicipta_pada'=> $this->task->getDiciptaPada(),
            'dikemaskini_pada'=> $this->task->getDikemaskiniPada()
        ];

        $barangIDs = $this->barang->getBarangID();
        $barangNames = $this->barang->getNamaBarang();
        $barangQuantities = $this->barang->getKuantiti();
        $barangHarga = $this->barang->getHargaTotalBarang();

        $array['barang'] = [];

        foreach ($barangIDs as $index => $id) {
            $array['barang'][] = [
                'barang_id_fetch' => $id,
                'barang_nama_fetch' => $barangNames[$index],
                'barang_kuantiti_fetch' => $barangQuantities[$index],
                'barang_hargaTotal_fetch' => $barangHarga[$index]
            ];
        }

        $array['finance'] = [
            'finance_id'=> $this->finance->getTaskFinanceID(),
            'harga_total'=> $this->finance->getTaskHargaTotal(),
            'status_pembayaran' => $this->finance->getTaskPaidBool(),
            'dibayar_pada' => $this->finance->getDibayarPada()
        ];

        return $array;
    }

    // public function fetchPreviousBarang() 
    // {
    //     $barangIDs = $this->barang->getBarangID();
    //     $barangNames = $this->barang->getNamaBarang();
    //     $barangQuantities = $this->barang->getKuantiti();
    //     $barangHarga = $this->barang->getHargaTotalBarang();

    //     $array = [];

    //     foreach ($barangIDs as $index => $id) {
    //         $array[] = [
    //             'barang_id' => $id,
    //             'barang_nama' => $barangNames[$index],
    //             'barang_kuantiti' => $barangQuantities[$index],
    //             'barang_hargaTotal' => $barangHarga[$index]
    //         ];
    //     }

    //     return $array;
    // }

    
    private function prepareBarangArray($taskBarang): array
    {
        $newBarangArray = [];
        
        foreach ($taskBarang as $barangData) {
            $newBarangArray[] = [
                'barang_id' => $barangData['barang_id'],
                'barang_name' => $barangData['barang_name'],
                'total_quantity' => $barangData['total_quantity'],
                'total_price' => $barangData['total_price']
            ];
        }
        return $newBarangArray;
    }
}