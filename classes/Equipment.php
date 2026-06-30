<?php

class Equipment
{
    private $conn;
    private $table_name = "equipment";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE equip_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function update($id, $name, $status, $description)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = ?, equip_status = ?, description = ? 
                  WHERE equip_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("sssi", $name, $status, $description, $id);

        return $stmt->execute();
    }

    public function create($name, $description, $imageTmpPath)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description, equip_status, image) 
                  VALUES (?, ?, 'disp', ?)";

        $stmt = $this->conn->prepare($query);

        if (!empty($imageTmpPath)) {
            $imageData = file_get_contents($imageTmpPath);
            $null = null;
            $stmt->bind_param("ssb", $name, $description, $null);
            $stmt->send_long_data(2, $imageData);
        } else {
            $null = null;
            $stmt->bind_param("sss", $name, $description, $null);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE equip_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function getAllWithStatus()
    {
        $sql = "SELECT e.*, 
            (SELECT COUNT(*) FROM reservation r 
             WHERE r.equip_id = e.equip_id 
             AND r.res_date = CURDATE()) as reservado_hoje
            FROM equipment e";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
