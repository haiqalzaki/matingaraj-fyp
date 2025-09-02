<?php

namespace App\Models;

class UserEntity
{
    private $id;
    private $name;
    private $nameSearch;
    private $email;
    private $password;
    private $phone;
    private $role;
    private $isActive;

    public function __construct($id = null) 
    {
        if ($id !== null) {
            $this->id = $id;
            $this->load();
        }
    }

    public function load(): void 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT * FROM garaj_user WHERE u_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            $data = $row->fetch_assoc();

            $this->name = $data["u_name"];
            $this->nameSearch = $data["u_name_search"];
            $this->email = $data["u_email"];
            $this->password = $data["u_password"];
            $this->phone = $data["u_phone"];
            $this->role = $data["u_isAdmin"];
            $this->isActive = $data["u_isActive"];
        }

        $stmt->close();
        $db->close();
    }

    public function create(): bool
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $duplicate = $this->isDuplicate();

        if ($duplicate) {
            return false;
        }

        $sql = "INSERT INTO garaj_user (
                u_name,
                u_name_search,
                u_email,
                u_password,
                u_phone,
                u_isAdmin,
                u_isActive) VALUES (?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for user insert: {$db->error}");
        }

        $stmt->bind_param("sssssii",
                            $this->name, 
                            $this->nameSearch, 
                            $this->email, 
                            $this->password, 
                            $this->phone, 
                            $this->role, 
                            $this->isActive
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

        $sql = "UPDATE garaj_user 
                SET 
                    u_name = ?, 
                    u_name_search = ?, 
                    u_email = ?, 
                    u_password = ?, 
                    u_phone = ?, 
                    u_isAdmin = ?, 
                    u_isActive = ? 
                WHERE u_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for user update: {$db->error}");
        }

        $stmt->bind_param("ssssssii", 
                        $this->name, 
                        $this->nameSearch, 
                        $this->email, 
                        $this->password, 
                        $this->phone, 
                        $this->role, 
                        $this->isActive,
                        $this->id 
                    );             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing update statement! Log: {$db->error}");
        }

        $stmt->close();
        $db->close();

        return true;
    }

    public function delete(): bool 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "UPDATE garaj_user 
                SET isDeleted = 1,  deletedOn = NOW()
                WHERE u_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for user delete: {$db->error}");
        }

        $stmt->bind_param("i", $this->id);             
    
        if (!$stmt->execute()) {
            throw new \Exception("Error executing delete statement! Log: {$db->error}");
        }

        $stmt->close();
        $db->close();

        return true;
    }

    public function build($array): void
    {
        $this->name = $array['name'];
        $this->nameSearch = $array['nameSearch'];
        $this->password = $array['password'];
        $this->email = $array['email'];
        $this->phone = $array['phone'];
        $this->role = $array['isAdmin'];
        $this->isActive = true;
    }

    public function get(): array|bool
    {
        if (!isset($this->id)) {
            return false;
        }

        $array = [
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'phone'=> $this->phone,
            'isAdmin'=> $this->role,
            'isActive'=> $this->isActive
        ];

        return $array;
    }

    public function isDuplicate(): bool 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 1 FROM garaj_user WHERE u_name = ? OR u_email = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for user insert: {$db->error}");
        }

        try {
            $stmt->bind_param("ss", $this->name, $this->email);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for user inserts: " . $e->getMessage());
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

        $sql = "SELECT 1 FROM garaj_user WHERE (u_name = ? OR u_email = ?) AND u_id != ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for pengguna insert: {$db->error}");
        }
        
        try {
            $stmt->bind_param("ssi", $this->name, $this->email, $this->id);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for user updates: " . $e->getMessage());
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

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getNameSearch() { return $this->nameSearch; }
    public function setNameSearch($nameSearch) { $this->nameSearch = $nameSearch; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }

    public function getPhone() { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; }

    public function getRole() { return $this->role; }
    public function setRole($role) { $this->role = $role; }

    public function getIsActive() { return $this->isActive; }
    public function setIsActive($isActive) { $this->isActive = $isActive; }
}