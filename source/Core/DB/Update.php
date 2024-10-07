<?php

namespace Autoload\Core\DB;

use Autoload\Core\Connect;
use PDO;
use PDOException;
use PDOStatement;

/**
 * Faz update no BD
 */
class Update extends Connect
{
    private ?PDO $conn;
    private ?PDOStatement $stmt;

    /**
     * Obtém a instância da superclass e inicializa a conexão
     */
    function __construct()
    {
        $this->conn = $this->getInstance();
    }

    /**
     * Executa uma atualização no banco de dados
     * @param string $table Nome da tabela onde os dados serão atualizados
     * @param array $data Array associativo com as colunas e valores a serem alterados (ex: ['name' => 'Bruno'])
     * @param string $where Condição para a atualização (ex: "id = :id")
     * @param array $params Array associativo com os parâmetros para o WHERE (ex: [':id' => $id])
     * @return bool TRUE se sucesso ou FALSE em caso de falha
     */
    public function update(string $table, array $data, string $where, array $params = []): bool
    {
        // Gerando a query dinâmica para a atualização
        $setPart = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($data)));
        $query = "UPDATE $table SET $setPart WHERE $where";

        try {
            $this->stmt = $this->conn->prepare($query);

            // Combinando os dados e parâmetros do WHERE
            $mergedParams = array_merge($data, $params);

            // Executando a query
            return $this->stmt->execute($mergedParams);
        } catch (PDOException $e) {
            echo "Erro de alteração: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtém o número de linhas afetadas pela última operação de atualização
     * @return int Número de linhas afetadas
     */
    public function getRowCount(): int
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }
}