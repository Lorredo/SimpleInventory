<?php

class Database {
    private $conn;
    
    // Constructor to establish database connection
    public function __construct($host, $username, $password, $database) {
        $this->conn = new mysqli($host, $username, $password, $database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get the database connection
    public function getConnection() {
        return $this->conn;
    }
}
