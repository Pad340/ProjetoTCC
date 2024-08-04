<?php

namespace Autoload\Core;

use PDO;
use PDOException;

abstract class Connect
{
    private string $host = 'localhost';
    private string $db_name = 'reifeitorio';
    private string $username = 'root';
    private string $password = '';

    protected function getInstance(): ?PDO
    {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $conn;
    }
}