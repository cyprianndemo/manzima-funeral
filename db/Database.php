<?php

class Database {
    // DB PARAMS
    private $host = 'localhost';
    private $db = 'manzima_db';
    private $username = 'postgres';
    private $pwd = '@Omega_2021';
    private $port = '5432';
    private $conn;

    // DB CONNECT
    public function connect() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
            $this->conn = new PDO($dsn, $this->username, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $this->conn;
    }
}