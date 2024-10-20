<?php

namespace Autoload\Core\DB;

use Autoload\Core\Connect;
use PDO;
use PDOException;

/**
 * Insere dados no BD
 */
class Insert extends Connect
{
    private ?PDO $conn;

    /**
     * Obtém a instância da superclass
     */
    function __construct()
    {
        $this->conn = $this->getInstance();
    }

    /**
     * Insere dados na tabela $table
     * @param string $table Nome da tabela
     * @param array $values Array associativo com as colunas e valores a serem inseridos. Por exemplo: ['user_id' => 1, 'name' => 'Bruno']
     * @return bool TRUE se der bom, FALSE caso acontecer algo
     */
    public function insert(string $table, array $values): bool
    {
        // Construindo a parte da query que contém os nomes das colunas
        $columns = implode(", ", array_keys($values));

        // Construindo a parte da query que contém os placeholders para os valores
        $placeholders = implode(", ", array_map(function ($key) {
            return ":$key";
        }, array_keys($values)));

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $stmt = $this->conn->prepare($query);

            foreach ($values as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtém o ID do último registro inserido
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->conn->lastInsertId();
    }
}