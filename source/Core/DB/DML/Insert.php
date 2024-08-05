<?php

namespace Autoload\Core\DB\DML;

use Autoload\Core\DB\Connect;
use PDO;
use PDOException;
use PDOStatement;

class Insert extends Connect
{
    /**
     * @var PDO|null
     */
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
     * @param array $values Array associativo com as colunas e valores a serem inseridos
     * @return bool
     */
    public function insert(string $table, array $values): bool
    {
        // Construindo a parte da query que contém os nomes das colunas
        $columns = implode(", ", array_keys($values));

        // Construindo a parte da query que contém os placeholders para os valores
        $placeholders = implode(", ", array_map(function ($key) {
            return ":$key";
        }, array_keys($values)));

        // Montando a query completa
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            // Preparando a query
            $stmt = $this->conn->prepare($query);

            // Bindando os valores aos placeholders
            foreach ($values as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            // Executando a query
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtém o ID do último registro inserido
     * @return string|null
     */
    public function getLastInsertId(): ?string
    {
        return $this->conn?->lastInsertId();
    }
}