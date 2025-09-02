<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Logger;
use App\Helper\Response;
use App\Models\BarangEntity;
use App\Models\BarangManager;
use App\Models\UploadHandler;

class Barang extends Controller 
{
    private $tajuk = 'Inventory';
    private $css = 'barang';

    public function index() 
    {
        if (!$this->isValidSession() || !$this->isValidAdmin()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/Barang/index', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit();
    }

    public function add($params = [], $paramFile = []) 
    { 
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $barangInfo = [
            'path'=> null,
            'name'=> $params['barangName'] ?? null,
            'nameSearch' => strtolower(str_replace(' ','', $params['barangName'] ?? null)),
            'stock' => isset($params['barangStok']) ? (int) $params['barangStok'] : 0,
            'modal' => isset($params['barangPrice']) ? (float) $params['barangPrice'] : 0.00,
            'markup' => isset($params['barangMarkup']) ? (float) $params['barangMarkup'] : 0.00,
            'remark'=> $params['barangRemark'] ?? 'No remarks',
        ];

        $barangInfo['total'] = $barangInfo['modal'] + $barangInfo['markup'];

        foreach ($barangInfo as $key => $value) {
            if ($key === 'name') {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    exit();
                }
                if ($value === '') {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty field!');
                    exit();
                } 
            } 
        }

        $barangImage = [
            'fileName'=> $paramFile['barangImage']['name'],
            'fileType' => $paramFile['barangImage']['type'],
            'fileTmpName'=> $paramFile['barangImage']['tmp_name'],
            'fileError'=> $paramFile['barangImage']['error'],
            'fileSize'=> $paramFile['barangImage']['size'],
        ];

        if ($barangImage['fileError'] !== 0 && $barangImage['fileError'] !== 4) {
            Response::returnJSON(false, 'Error occured during uploading!');
            Logger::log('File error!');
            exit();
        }

        if ($barangImage['fileName'] === '') {
            $barangInfo['path'] = '/image/no-img.jpg';
        } else {
            try {
                $upload = new UploadHandler($barangImage);

                if (!$upload->upload()) {
                    Response::returnJSON(false, 'Upload failed! Invalid file type.');
                    Logger::log('File upload failed during upload. Invalid file type.');
                    exit();
                }
    
                $barangInfo['path'] = $upload->getPath();
            } catch (\Exception $e) {
                Response::returnJSON(false, 'Error during upload to filesystem!');
                Logger::log('Exception caught: '. $e->getMessage());
                exit();
            }
        }

        try {
            $barangInsert = new BarangEntity(); 
            $barangInsert->build($barangInfo);

            if (!$barangInsert->create()) {
                Response::returnJSON(false, 'Adding barang failed! Barang already exist.');
                exit();
            }

            Response::returnJSON(true, 'Barang added successfully!');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed adding barang!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function update($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        // INPUT
        $barang = [
            "id" => (int) $params['barangID'] ?? null,
            "name" => $params['barangName'] ?? null,
            "nameSearch" => strtolower(str_replace(' ','', $params['barangName'] ?? null)),
            'stock' => isset($params['barangStock']) ? (int) $params['barangStock'] : 0,
            'modal' => isset($params['barangPrice']) ? (float) $params['barangPrice'] : 0.00,
            'markup' => isset($params['barangMarkup']) ? (float) $params['barangMarkup'] : 0.00,
            'remark'=> $params['barangRemark'] ?? "No remarks",
        ];

        $barang['total'] = $barang['modal'] + $barang['markup']; 

        // PROSES
        try {
            $barangUpdate = new BarangEntity($barang['id']);

            // Update 
            $barangUpdate->setName($barang['name']);
	        $barangUpdate->setNameSearch($barang['nameSearch']);
            $barangUpdate->setStock($barang['stock']);
            $barangUpdate->setPrice($barang['modal']);
            $barangUpdate->setMarkup($barang['markup']);
            $barangUpdate->setRemark($barang['remark']);
            $barangUpdate->setTotal($barang['total']);

            if (!$barangUpdate->save()) {
                Response::returnJSON(false, 'Update failed. Barang already exist.');
                exit();
            }
            // OUTPUT
            Response::returnJSON(true, "Success updating barang data!");
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed to update barang data from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function delete($params = []) 
    {
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $id = (int) $params['barangID'] ?? null;

        try {
            $cxDelete = new BarangEntity($id);
            $cxDelete->delete();

            Response::returnJSON(true,'Barang deleted successfully.');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed deleting barang from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function get($params = []) 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $id = (int) isset($params[0]) ? $params[0] : null;

        if ($id === null) {
            Response::returnJSON(false,'Invalid request.', $params);
            exit();
        }

        try {
            $user = new BarangEntity($id);
        
            $data['name'] = $user->getName();
            $data['stock'] = $user->getStock();
            $data['price'] = $user->getPrice();
            $data['markup'] = $user->getMarkup();
            $data['total'] = $user->getTotal();
            $data['remark'] = $user->getRemark();
            $data['img'] = $user->getImagePath();

            Response::returnJSON(true, "Success fetching barang data!", $data);
        }
        catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching barang from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        } 
    }

    public function getAll() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $barang = new BarangManager();

        try {
            $data = $barang->fetchAll();

            $data = array_map(function($barang) {
                return array_map('htmlspecialchars', $barang);
            }, $data);

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching barang data from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }     
    }

    public function available() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $barang = new BarangManager();

        try {
            $data = $barang->fetchBarangAvailable();

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching barang data from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    public function fetchStats() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $barang = new BarangManager();

        try {
            $data = $barang->mostUsedBarang();

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching barang stats from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }
}