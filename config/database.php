<?php

class Database {
    private $host = "";
    private $db_name = "";
    private $username = "";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );

            if ($this->conn->connect_error) {
                die(json_encode([
                    "success" => false,
                    "message" => "Database Connection Failed",
                    "error" => $this->conn->connect_error
                ]));
            }

            $this->conn->set_charset("utf8mb4");

        } catch (Exception $exception) {
            echo json_encode([
                "success" => false,
                "message" => "Database Error",
                "error" => $exception->getMessage()
            ]);
        }

        return $this->conn;
    }
}
