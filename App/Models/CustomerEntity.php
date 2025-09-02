<?php

namespace App\Models;

class CustomerEntity 
{
    private $c_id;
    private $c_name;
    private $c_nameSearch;
    private $c_email;
    private $c_phone;
    private $c_platform;
    private $c_remark;

    public function __construct($cx_id = null) 
    {
        if ($cx_id !== null) {
            $this->c_id = $cx_id;
            $this->load();
        }
    }

    public function load() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT * FROM garaj_customer WHERE c_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->c_id);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            $data = $row->fetch_assoc();

            $this->c_name = $data["c_name"];
            $this->c_nameSearch = $data["c_name_search"];
            $this->c_phone = $data["c_phone"];
            $this->c_email = $data["c_email"];
            $this->c_platform = $data["c_platform"];
            $this->c_remark = $data["c_remark"];
        }

        $stmt->close();
        $db->close();
    }

    public function create() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $duplicate = $this->isDuplicate();

        if ($duplicate) {
            return false;
        }

        $sql = "INSERT INTO garaj_customer (
                c_name,
                c_name_search,
                c_email,
                c_phone,
                c_platform,
                c_remark) VALUES (?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for customer insert: {$db->error}");
        }

        $stmt->bind_param("ssssss",
                            $this->c_name, 
                            $this->c_nameSearch, 
                            $this->c_email, 
                            $this->c_phone,
                            $this->c_platform,
                            $this->c_remark,
                        );                    
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing insert statement! Log: {$db->error}");
        }

        $stmt->close();
        $db->close();

        return true;
    }

    public function save(): bool
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $duplicate = $this->isDuplicateSave();

        if ($duplicate) {
            return false;
        }

        $sql = "UPDATE garaj_customer 
                SET 
                    c_name = ?, 
                    c_name_search = ?, 
                    c_email = ?, 
                    c_phone = ?, 
                    c_platform = ?, 
                    c_remark = ? 
                WHERE c_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for customer update: {$db->error}");
        }

        $stmt->bind_param("ssssssi", 
                        $this->c_name, 
                        $this->c_nameSearch, 
                        $this->c_email, 
                        $this->c_phone, 
                        $this->c_platform, 
                        $this->c_remark,
                        $this->c_id 
                    );             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing update statement! Log: {$db->error}");
        }

        $stmt->close();
        $db->close();

        return true;
    }

    public function delete() 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "UPDATE garaj_customer 
                SET isDeleted = 1, deletedOn = NOW()
                WHERE c_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for customer delete: {$db->error}");
        }

        $stmt->bind_param("i", $this->c_id);         
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing delete statement! Log: {$db->error}");
        }

        $stmt->close();
        $db->close();

        return true;
    }

    public function build($array) 
    {
        $this->c_name = $array['name'];
        $this->c_nameSearch = $array['nameSearch'];
        $this->c_email = $array['email'];
        $this->c_phone = $array['phone'];
        $this->c_platform = $array['platform'];
        $this->c_remark = $array['remark'];
    }

    public function isDuplicate(): bool 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 1 FROM garaj_customer WHERE c_name = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for customer insert: {$db->error}");
        }
        
        try {
            $stmt->bind_param("s", $this->c_name);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for customer inserts: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("General error: " . $e->getMessage());
        } finally {
            $stmt->close();
            $db->close();
        }

        if ($count > 0) {
            return true;
        }   
        return false;
    }

    public function isDuplicateSave(): bool 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 1 FROM garaj_customer WHERE (c_name = ?) AND c_id != ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for customer update: {$db->error}");
        }
        
        try {
            $stmt->bind_param("si", $this->c_name, $this->c_id);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for customer updates: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("General error: " . $e->getMessage());
        } finally {
            $stmt->close();
            $db->close();
        }

        if ($count > 0) {
            return true;
        }   
        return false;
    }

    public function getId() { return $this->c_id; }
    public function setId($id) { $this->c_id = $id; }

    public function getName() { return $this->c_name; }
    public function setName($name) { $this->c_name = $name; }

    public function getEmail() { return $this->c_email; }
    public function setEmail($email) { $this->c_email = $email; }

    public function getPhone() { return $this->c_phone; }
    public function setPhone($phone) { $this->c_phone = $phone; }
    
    public function getPlatform() { return $this->c_platform; }
    public function setPlatform($platform) { $this->c_platform = $platform; }

    public function getRemark() { return $this->c_remark; }
    public function setRemark($remark) { $this->c_remark = $remark; }
}