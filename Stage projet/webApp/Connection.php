<?php

class Connection {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        // Create connection
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password);
        // Check connection
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function createDatabase($dbName) {
        // Create a database with the conn in the class ($this->conn)
        $sql = "CREATE DATABASE $dbName";
        if (mysqli_query($this->conn, $sql)) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: " . mysqli_error($this->conn);
        }
    }

    public function selectDatabase($dbName) {
        // Select database with the conn of the class
        mysqli_select_db($this->conn, $dbName);
    }

    public function createTable($query) {
        if (mysqli_query($this->conn, $query)) {
            // Dynamically output the table name if it's in the query
            preg_match("/CREATE TABLE IF NOT EXISTS (\w+)/", $query, $matches);
            $tableName = isset($matches[1]) ? $matches[1] : 'unknown';
            echo "Table $tableName created successfully";
        } else {
            echo "Error creating table: " . mysqli_error($this->conn);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __destruct() {
        // Close the connection when the object is destroyed
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}

?>
