<?php

class Database {
    private $host = "193.203.184.211";
    private $db_name = "u508697926_form_db";
    private $username = "u508697926_shri";
    private $password = "J+8kAjqP?xDa%Jp";
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