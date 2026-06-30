<?php

class Laboratory
{
    private $conn;
    private $table_name = "laboratory";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function getAll()
    {
        $query = "SELECT lab_id, room_number, description, capacity, lab_status, image, created_at 
                  FROM " . $this->table_name . " 
                  ORDER BY room_number ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function create($room_number, $description, $capacity, $imageTmpPath)
    {
        $query = "INSERT INTO " . $this->table_name . " (room_number, description, capacity, lab_status, image) 
              VALUES (?, ?, ?, 'disp', ?)";

        $stmt = $this->conn->prepare($query);

        if (!empty($imageTmpPath)) {
            $null = null;
            $stmt->bind_param("ssib", $room_number, $description, $capacity, $null);
            $stmt->send_long_data(3, file_get_contents($imageTmpPath));
        } else {
            $null = null;
            $stmt->bind_param("ssis", $room_number, $description, $capacity, $null);
        }

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }

        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE lab_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM laboratory WHERE lab_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function update($id, $room_number, $description, $capacity, $lab_status)
    {
        $query = "UPDATE laboratory 
              SET room_number = ?, 
                  description = ?, 
                  capacity = ?, 
                  lab_status = ? 
              WHERE lab_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("ssisi", $room_number, $description, $capacity, $lab_status, $id);

        return $stmt->execute();
    }

    public function getAllWithStatus()
    {
        $sql = "SELECT l.*, 
            (SELECT COUNT(*) FROM reservation r 
             WHERE r.lab_id = l.lab_id 
             AND r.res_date = CURDATE()) as reservado_hoje
            FROM laboratory l";

        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
