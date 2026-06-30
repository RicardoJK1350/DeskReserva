<?php
// classes/User.php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT user_id, name AS username, cpf, email, password, user_type FROM user WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user;
    }

    public function getProfile($userId)
    {
        $query = "
            SELECT u.name AS username, u.cpf, u.email, u.user_type, p.foto_perfil
            FROM user AS u
            LEFT JOIN perfil AS p ON u.user_id = p.user_id
            WHERE u.user_id = ?
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        $stmt->close();

        return $profile;
    }

    public function updateProfilePhoto($userId, $conteudoImagem)
    {
        $stmt = $this->conn->prepare("SELECT user_id FROM perfil WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();
        $existe = $stmt->num_rows > 0;
        $stmt->close();

        $null = null;
        if ($existe) {
            $stmt = $this->conn->prepare("UPDATE perfil SET foto_perfil = ? WHERE user_id = ?");
            $stmt->bind_param("bi", $null, $userId);
            $stmt->send_long_data(0, $conteudoImagem);
            $stmt->execute();
        } else {
            $stmt = $this->conn->prepare("INSERT INTO perfil (user_id, foto_perfil) VALUES (?, ?)");
            $stmt->bind_param("ib", $userId, $null);
            $stmt->send_long_data(1, $conteudoImagem);
            $stmt->execute();
        }
        $stmt->close();
        return true;
    }

    public function register($name, $cpf, $email, $phone, $password, $user_type = 'usu')
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO user (name, cpf, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $cpf, $email, $phone, $hash, $user_type);

        $result = $stmt->execute();

        if (!$result) {
            error_log("User registration failed: " . $this->conn->error);
        }

        return $result;
    }

    public function findByEmailAndPhone($email, $phone)
    {
        $stmt = $this->conn->prepare("SELECT user_id, name, cpf, email, phone FROM user WHERE email = ? AND phone = ? LIMIT 1");
        $stmt->bind_param('ss', $email, $phone);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updatePassword($userId, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        $stmt->bind_param('si', $hash, $userId);
        return $stmt->execute();
    }

    public function getAllUsers()
    {
        $result = $this->conn->query("SELECT user_id, name, cpf, email, user_type, phone FROM user");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT user_id, name, cpf, email, user_type, phone FROM user WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateType($id, $newType)
    {
        $stmt = $this->conn->prepare("UPDATE user SET user_type = ? WHERE user_id = ?");
        $stmt->bind_param('si', $newType, $id);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM user WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function setResetToken($userId, $token, $expiryTime)
    {
        $stmt = $this->conn->prepare("UPDATE user SET reset_token = ?, reset_expires = ? WHERE user_id = ?");
        $stmt->bind_param('ssi', $token, $expiryTime, $userId);
        return $stmt->execute();
    }

    public function getUserByResetToken($token)
    {
        $currentTime = date("Y-m-d H:i:s");
        $stmt = $this->conn->prepare("SELECT user_id FROM user WHERE reset_token = ? AND reset_expires > ? LIMIT 1");
        $stmt->bind_param('ss', $token, $currentTime);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function resetPassword($userId, $newPassword)
    {

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE user SET password = ?, reset_token = NULL, reset_expires = NULL WHERE user_id = ?");

        if (!$stmt) {
            error_log("DEBUG: Erro no prepare: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param('si', $hash, $userId);
        $stmt->execute();

        $affected = $this->conn->affected_rows;
        $stmt->close();

        // Se não atualizou, retornamos falso
        if ($affected === 0) {
            error_log("DEBUG: Nenhuma linha afetada para o user_id: $userId");
            return false;
        }

        return true;
    }
    public function updateAdmin($id, $name, $email, $cpf, $phone)
    {
        $query = "UPDATE user SET name = ?, email = ?, cpf = ?, phone = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $name, $email, $cpf, $phone, $id);
        return $stmt->execute();
    }
}
