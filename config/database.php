<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "spark_db";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        
        return $this->conn;
    }

    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
