<?php

class Reservation
{
    private $conn;
    private $table_name = "reservation";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($user_id, $equip_id, $lab_id, $turno, $res_date)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, equip_id, lab_id, turno, res_date, reservation_status) 
                  VALUES (?, ?, ?, ?, ?, 'pendente')";

        $stmt = $this->conn->prepare($query);

        $equip = !empty($equip_id) ? $equip_id : null;
        $lab = !empty($lab_id) ? $lab_id : null;

        $stmt->bind_param("iiiss", $user_id, $equip, $lab, $turno, $res_date);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Erro no MySQL: " . $stmt->error);
            return false;
        }
    }

    public function getByUserId($user_id)
    {
        $query = "SELECT r.reservation_id, r.turno, r.res_date, r.reservation_status,
                         e.name as equip_name, l.room_number as lab_room
                  FROM " . $this->table_name . " r
                  LEFT JOIN equipment e ON r.equip_id = e.equip_id
                  LEFT JOIN laboratory l ON r.lab_id = l.lab_id
                  WHERE r.user_id = ?
                  ORDER BY r.res_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function cancel($reservation_id, $user_id)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET reservation_status = 'cancelada' 
                  WHERE reservation_id = ? AND user_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $reservation_id, $user_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return true;
        }

        return false;
    }
}
