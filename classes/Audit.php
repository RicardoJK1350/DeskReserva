<?php
// classes/Audit.php

class Audit
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Grava uma nova ação no log de auditoria
     */
    public function logAction($userId, $actionType, $details)
    {
        $stmt = $this->conn->prepare("INSERT INTO audit_log (user_id, action_type, details) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userId, $actionType, $details);
        return $stmt->execute();
    }

        public function getLogs()
        {
            $query = "SELECT a.timestamp, u.name AS username, a.action_type, a.details 
                FROM audit_log a
                INNER JOIN user u ON a.user_id = u.user_id
                ORDER BY a.timestamp DESC";
                
        $result = $this->conn->query($query);
            
            $logs = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $logs[] = $row;
                }
            }
            return $logs;
        }
    }