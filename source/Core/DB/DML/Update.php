<?php

namespace Autoload\Core\DB\DML;

use Autoload\Core\DB\Connect;
use PDO;
use PDOStatement;

class Update extends Connect
{
    /**
     * @var PDO|null
     */
    private ?PDO $conn;

    /**
     * @var PDOStatement|null
     */
    private ?PDOStatement $stmt;

    /**
     * Obtém a instância da superclass
     */
    function __construct()
    {
        $this->conn = $this->getInstance();
    }
}