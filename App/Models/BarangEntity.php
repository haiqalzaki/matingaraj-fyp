<?php

namespace App\Models;

class BarangEntity
{
    private $id;
    private $imagePath;
    private $name;
    private $nameSearch;
    private $stock;
    private $price;
    private $markup;
    private $total;
    private $remark;

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

        $sql = "SELECT * FROM garaj_inventory WHERE i_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();

        $row = $stmt->get_result();

        if ($row->num_rows > 0) {
            $data = $row->fetch_assoc();

            $this->imagePath = $data["i_image_path"];
            $this->name = $data["i_name"];
            $this->nameSearch = $data["i_name_search"];
            $this->stock = $data["i_stock"];
            $this->price = $data["i_cost"];
            $this->markup = $data["i_markup"];
            $this->total = $data["i_totalPrice"];
            $this->remark = $data["i_remark"];
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

        $sql = "INSERT INTO garaj_inventory (
                i_image_path,
                i_name,
                i_name_search,
                i_stock,
                i_cost,
                i_markup,
                i_totalPrice,
                i_remark) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for item insert: {$db->error}");
        }

        $stmt->bind_param("sssiddds",
                            $this->imagePath,
                            $this->name,
                            $this->nameSearch,
                            $this->stock,
                            $this->price,
                            $this->markup,
                            $this->total,
                            $this->remark
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

        $sql = "UPDATE garaj_inventory 
                SET 
                    i_name = ?, 
                    i_name_search = ?, 
                    i_stock = ?, 
                    i_cost = ?, 
                    i_markup = ?, 
                    i_totalPrice = ?, 
                    i_remark = ? 
                WHERE i_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for item update: {$db->error}");
        }

        $stmt->bind_param("ssidddsi", 
                        $this->name, 
                        $this->nameSearch, 
                        $this->stock, 
                        $this->price, 
                        $this->markup, 
                        $this->total, 
                        $this->remark,
                        $this->id 
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

        $sql = "UPDATE garaj_inventory 
                SET isDeleted = 1,  deletedOn = NOW()
                WHERE i_id = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for item delete: {$db->error}");
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
        $this->imagePath = $array['path'];
        $this->name = $array['name'];
        $this->nameSearch = $array['nameSearch'];
        $this->stock = $array['stock'];
        $this->price = $array['modal'];
        $this->markup = $array['markup'];
        $this->total = $array['total'];
        $this->remark = $array['remark'];
    }

    public function isDuplicate(): bool 
    {
        $db = new \mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

        $sql = "SELECT 1 FROM garaj_inventory WHERE i_name = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for item insert: {$db->error}");
        }

        try {
            $stmt->bind_param("s", $this->name);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for item inserts: " . $e->getMessage());
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

        $sql = "SELECT 1 FROM garaj_inventory WHERE (i_name = ?) AND i_id != ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            throw new \Exception("Error preparing statement for item insert: {$db->error}");
        }
        
        try {
            $stmt->bind_param("si", $this->name,$this->id);
            if (!$stmt->execute()) {
                throw new \Exception("Error executing statement: {$stmt->error}");
            }
            $stmt->bind_result($count);
            $stmt->fetch();
        } catch (\ArgumentCountError $e) {
            throw new \Exception("Parameter count mismatch for item updates: " . $e->getMessage());
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

    public function getImagePath() { return $this->imagePath; } 
    public function setImagePath($imagePath) { $this->imagePath = $imagePath; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getNameSearch() { return $this->nameSearch; }
    public function setNameSearch($nameSearch) { $this->nameSearch = $nameSearch; }

    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; }

    public function getPrice() { return $this->price; } 
    public function setPrice($price) { $this->price = $price; }

    public function getMarkup() { return $this->markup; }
    public function setMarkup($markup) { $this->markup = $markup; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getRemark() { return $this->remark; }   
    public function setRemark($remark) { $this->remark = $remark; }
}