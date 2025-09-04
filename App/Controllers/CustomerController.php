<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Helper\Utiliti;
use App\Models\CustomerEntity;
use App\Models\CustomerManager;
use App\Helper\Response;
use App\Helper\Logger;
use App\Controllers\User;

class Customer extends Controller 
{
    private $tajuk = 'Customer';
    private $css = 'customer';

    public function index() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $data['tajuk'] = $this->tajuk;
        $data['css'] = $this->css;
        
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/Customer/index', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit();
    }

    public function detail($params = []) {

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
                                                                  
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    
        // Input
        $id = $params[0]; // ID pelanggan dari URL
    
        // Dapatkan data pelanggan
        $stmt = $db->prepare("SELECT * FROM garaj_customer WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data['customer'] = [];
    
        while ($row = $result->fetch_assoc()) 
        {
            // Setkan data pelanggan
            $data['customer']['nama'] = $row['c_name'];
            $data['customer']['tel'] = $row['c_phone'];
            $data['customer']['platform'] = $row['c_platform'];
            $data['customerid'] = $row['c_id'];
        }
    
        // Dapatkan senarai task pelanggan
        $stmt = $db->prepare("SELECT * FROM garaj_task WHERE c_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data['task'] = [];
    
        while ($row = $result->fetch_assoc()) 
        {
            $data['task'][] = $row; // Simpan semua task pelanggan dalam array
        }
    
        // Kira jumlah task pelanggan
        $stmt = $db->prepare("SELECT COUNT(*) AS jumlah FROM garaj_task WHERE c_id = ?"); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $data['customer']['jumlah-task'] = $row['jumlah']; // Simpan jumlah task
        }
    
        // Output Data
        $data['tajuk'] = $this->tajuk;
        $data['css'] = 'detailcustomer';
    
        // Hantar data ke View
        $this->view('Partials/head', $data);
        $this->view('Partials/navigation');
        $this->view('Partials/open-wrapper');
        $this->view('Partials/navbar', $data);
        $this->view('Templates/Customer/detailcustomer', $data);
        $this->view('Partials/close-wrapper');
        $this->view('Partials/footer');
        exit(); // Pastikan exit terakhir selepas semua data dihantar ke view
    }

    public function add($params = []) 
    { 
        if ($this->isGET()) {
            $this->index();
        }

        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $customer = [
            'name'=> $params['cxName'] ?? null,
            "nameSearch" => strtolower(str_replace(' ','', $params['cxName'] ?? '')),
            'email'=> $params['cxEmail'] ?? 'Tiada',
            'phone'=> $params['cxPhone'] ?? 'Tiada',
            'platform'=> $params['cxPlatform'] ?? null,
            'remark'=> $params['cxRemark'] ?? "No remarks" ,
        ];

        // Input validation
        foreach ($customer as $key => $value) {
            if ($key === 'name' || $key === 'platform') {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Name or platform value is null!');
                    exit();
                }
                if ($value === '') {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty field!');
                    Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Name or platform value is blank!');
                    exit();
                } 
            } 
            if ($key === 'remark') {
                if (strlen($value) > 500) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be more than 500 characters!');
                    Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Remarks length!');
                    exit();
                }
            }
            if ($key === 'name') {
                if (strlen($value) < 3) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be less than 2 characters!');
                    Logger::log('Error logged: Modifying customer for customer update failed in Customer Controller. Reason: Name length is less than 5 characters, invalid!');
                    exit();
                }
            }

	    if ($key === 'phone') {
    		if (strlen($value) < 9 || strlen($value) > 12) {
       			Response::returnJSON(false, ucfirst($key) . ' number is invalid! Must be between 9 and 12 characters.');
        		Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Phone length!');
        		exit();
    		}
	    } 
        }

        // Database query
        try {
            $customerInsert = new CustomerEntity(); 
            $customerInsert->build($customer);
            if (!$customerInsert->create()) {
                Response::returnJSON(false, 'Adding customer failed. Name or email already exist.');
                Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Error on main try block during creation!');
                exit();
            }
            Response::returnJSON(true, 'Adding customer successful!');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed adding customer!');
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

        // Input
        $customer = [
            "id" => (int) $params['cxID'] ?? null,
            "name" => $params['cxName'] ?? null,
            "nameSearch" => strtolower(str_replace(' ','', $params['cxName'] ?? null)),
            'email'=> $params['cxEmail'] ?? 'Tiada',
            'phone'=> $params['cxPhone'] ?? 'Tiada',
            'platform'=> $params['cxPlatform'] ?? null,
            'remark'=> $params['cxRemark'] ?? "No remarks",
        ];

        // Input Validation
        foreach ($customer as $key => $value) {
            if ($key === 'id' || $key === 'name' || $key === 'platform') {
                if (!isset($value) || $value === null) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be empty!');
                    Logger::log('Error logged: Modifying customer for customer update failed in Customer Controller. Reason: Empty id/name/platform input!');
                    exit();
                }
            }
            if ($key === 'name') {
                if (strlen($value) < 3) {
                    Response::returnJSON(false, ucfirst($key) . ' cannot be less than 2 characters!');
                    Logger::log('Error logged: Modifying customer for customer update failed in Customer Controller. Reason: Name length is less than 5 characters, invalid!');
                    exit();
                }
            }
	    if ($key === 'phone') {
    		if (strlen($value) < 9 || strlen($value) > 12) {
       			Response::returnJSON(false, ucfirst($key) . ' number is invalid! Must be between 9 and 12 characters.');
        		Logger::log('Error logged: Adding customer for customer creation failed in Customer Controller. Reason: Phone length!');
        		exit();
    		}
	    }
        }

        // Process
        try {
            $cxUpdate = new CustomerEntity($customer['id']);

            // Update 
            $cxUpdate->setName($customer['name']);
            $cxUpdate->setEmail($customer['email']);
            $cxUpdate->setPhone($customer['phone']);
            $cxUpdate->setPlatform($customer['platform']);
            $cxUpdate->setRemark($customer['remark']);

            if (!$cxUpdate->save()) {
                Response::returnJSON(false, 'Update failed. Customer name already exist.');
                Logger::log('Error logged: Modifying customer for customer update failed in Customer Controller. Reason: Error on main try block during save/update!');
                exit();
            }
            // Output
            Response::returnJSON(true, "Success updating customer data!");
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed to update customer data from database!');
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

        $id = isset($params['cxID']) ? (int) $params['cxID'] : null;

        if ($id === null) {
            Response::returnJSON(false, 'ID is NULL!');
            Logger::log('Error logged: Modifying customer for customer deletion failed in Customer Controller. Reason: Empty id!');
            exit();
        }

        try {
            $cxDelete = new CustomerEntity($id);
            $cxDelete->delete();

            Response::returnJSON(true,'Customer deleted successfully.');
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed deleting customer from database!');
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
            Response::returnJSON(false, 'ID is NULL!');
            Logger::log('Error logged: Fetching customer failed in Customer Controller. Reason: Empty id!');
            exit();
        }

        try {
            $user = new CustomerEntity($id);
        
            $data['name'] = $user->getName();
            $data['email'] = $user->getEmail();
            $data['phone'] = $user->getPhone();
            $data['platform'] = $user->getPlatform();
            $data['remark'] = $user->getRemark();

            Response::returnJSON(true, "Success fetching customer data!", $data);
        }
        catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching customer from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        } 
    }

    public function getAll() 
    {
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }

        $cx = new CustomerManager();

        try {
            $data = $cx->fetchAll();

            $data = array_map(function($customer) {
                return array_map('htmlspecialchars', $customer);
            }, $data);

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching customers from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }     
    }

    public function statistics() 
    {
        $cx = new CustomerManager();

        try {
            $data = $cx->fetchTotalByPlatform();

            Response::returnJSON(true, "Success fetching data!", $data);
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching customer statistics from database!');
            Logger::log('Exception caught: '. $e->getMessage());
        }   
    }

    public function search($params = []) 
    {
        header("content-type: application/json");

        $query = isset($params[0]) ? trim($params[0]) : '';

        $cx = new CustomerManager();

        try {
            $customerData = $cx->fetchAll();

            $customerData = array_map(function($row) {
                return array_map('htmlspecialchars', $row);
            }, $customerData);

            $filteredCustomer = array_filter($customerData, function($row) use ($query) {
                return stripos($row['c_name_search'], $query) !== false;
            });
            
            echo json_encode(array_values($filteredCustomer));
        } catch (\Exception $e) {
            Response::returnJSON(false, 'Failed fetching customers for searching!');
            Logger::log('Exception caught: '. $e->getMessage());
        }
    }

    
    
    public function dapatkanTask($params=[])
    {                
        if (!$this->isValidSession()) {
            header('Location: ' . $_ENV['HOME_URL'] . '/home');
            exit();
        }
                                              
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
    
        // Input
        $id = isset($params[0]) ? (int) $params[0] : 0;
    
        // Mula Proses: Dapatkan data pelanggan
        $stmt = $db->prepare("SELECT t_plate, createdOn, t_totalPrice,t_serviceStatus,t_id FROM garaj_task WHERE c_id = ? AND isDeleted = 0 ORDER BY createdOn DESC");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data = [];
    
        while ($row = $result->fetch_assoc()){
            $data[]= $row;
        }
    
        Response::returnJSON(true,null,$data);
    }
}