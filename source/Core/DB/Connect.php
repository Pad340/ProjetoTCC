<?php

namespace Autoload\Core\DB;

use PDO;
use PDOException;

abstract class Connect
{
    private string $host = CONF_DB_HOST;
    private string $db_name = CONF_DB_NAME;
    private string $username = CONF_DB_USER;
    private string $password = CONF_DB_PASS;

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