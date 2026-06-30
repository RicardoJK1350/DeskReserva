<?php

class Database
{
    private $host = "localhost";                
    private $db_name = "DeskReservaDB";
    private $username = "root";                 
    private $password = "jk123456";             
    public $conn;                               

    
    public function getConnection()
    {
        $this->conn = null; 

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $e) {
            $msgLog = sprintf(
                "[%s] [MySQL Erro %d]: %s em %s na linha %d%s",
                date('d/m/Y H:i:s'),
                $e->getCode(), 
                $e->getMessage(),
                basename($e->getFile()),
                $e->getLine(),
                PHP_EOL
            );

            $pastaLog = dirname(__DIR__) . '/log';
            if (!is_dir($pastaLog)) {
                mkdir($pastaLog, 0777, true);
            }

            $arquivoLog = $pastaLog . '/erros.log';
            error_log($msgLog, 3, $arquivoLog);

            echo "Erro ao conectar ao banco de dados. Tente novamente mais tarde.";
            exit;
        }
        return $this->conn; 
    }
}