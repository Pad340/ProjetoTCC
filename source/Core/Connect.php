<?php

namespace Autoload\Core;

use PDO;
use PDOException;

/**
 * Faz a conexão com o BD e deixa para apenas as subclasses acessarem
 */
abstract class Connect
{
    private string $host = DB_HOST;
    private string $db_name = DB_NAME;
    private string $username = DB_USER;
    private string $password = DB_PASS;

    /**
     * Faz a conexão com o BD.
     * @return PDO|null
     */
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