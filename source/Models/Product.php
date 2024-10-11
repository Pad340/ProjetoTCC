<?php

namespace Autoload\Models;

use Autoload\Core\DB\Insert;
use Autoload\Core\DB\Select;
use Autoload\Core\DB\Update;
use Autoload\Core\Session;

/**
 * Gerencia os produtos
 */
class Product
{
    private string $message = '';
    private string $messageType = '';
    const Table = 'product';

    /**
     * Faz o cadastro de um produto no banco de dados
     * @param string $name
     * @param int $category_id
     * @param string $price
     * @param int $qtt_stock
     * @return bool
     */
    public function register(string $name, int $category_id, string $price, int $qtt_stock): bool
    {
        $product = $this->attempt($name, $category_id, $price, $qtt_stock);

        if (!$product) return false;

        $insert = new Insert();
        if (!$insert->insert(self::Table, $product)) {
            $this->message = 'Ocorreu um erro ao cadastrar os dados.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        $this->message = 'Produto cadastrado com sucesso.';
        $this->messageType = ALERT_SUCCESS;
        return true;
    }

    /**
     * Atualiza os dados do produto
     * @param int $product_id
     * @param string $newName
     * @param int $newCategory
     * @param string $newPrice
     * @param int $newQtt_stock
     * @param int $newStatus_product
     * @return bool
     */
    public function update(int $product_id, string $newName, int $newCategory, string $newPrice, int $newQtt_stock, int $newStatus_product): bool
    {
        $newProduct = $this->checkChanges($product_id, $newName, $newCategory, $newPrice, $newQtt_stock, $newStatus_product);

        if (empty($newProduct)) return false;

        $update = new Update();
        if (!$update->update(self::Table, $newProduct, 'product_id = :id', [':id' => $product_id])) {
            $this->message = 'Ocorreu um erro ao atualizar os dados.';
            $this->messageType = ALERT_ERROR;
            return false;
        }

        $this->message = 'Produto editado com sucesso.';
        $this->messageType = ALERT_SUCCESS;
        return true;
    }

    /**
     * Retorna uma mensagem de resposta
     * @return string
     */
    public function getMessage(): string
    {
        return (new Alert($this->message, $this->messageType))->getHtml();
    }

    #####################
    ## Private Methods ##
    #####################

    /**
     * Faz as validações para cadastrar um produto
     * @param string $name
     * @param int $category_id
     * @param string $price
     * @param int $qtt_stock
     * @return array|null
     */
    public function attempt(string $name, int $category_id, string $price, int $qtt_stock): ?array
    {
        $session = new Session();
        $search = new Select();

        $product = $search->selectAll(self::Table, 'WHERE seller_id = :id AND name = :n', "id={$session->authSeller}&n={$name}", 'product_id');

        if ($product) {
            $this->message = 'Este produto já esta cadastrado!';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        // Nome
        if (strlen($name) <= 3 or strlen($name) > 100) {
            $this->message = 'O nome do produto deve ter entre 3 e 100 caracteres.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        // Categoria
        $category = $search->selectFirst('category', 'WHERE category_id = :id', "id={$category_id}", 'category_id');
        if (!$category) {
            $this->message = 'Categoria inválida.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        // Preço
        if (strlen($price) > 12) {
            $this->message = 'Preço muito elevado para o produto.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        if ($price < 0.05) {
            $this->message = 'Preço muito baixo, o valor mínimo é R$ 0,05.';
            $this->messageType = ALERT_WARNING;
            return null;
        }
        $price = brl_to_decimal($price);

        // Estoque
        if ($qtt_stock > 0 and $qtt_stock > 5000) {
            $this->message = 'Quantidade em estoque inválida. Min: 1 | Max: 5000';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        return [
            'name' => $name,
            'category_id' => $category_id,
            'price' => $price,
            'qtt_stock' => $qtt_stock,
            'seller_id' => $session->authSeller
        ];
    }

    /**
     * Verifica as mudanças
     * @param int $product_id
     * @param string $newName
     * @param int $newCategory
     * @param string $newPrice
     * @param int $newQtt_stock
     * @param int $newStatus_product
     * @return array|null Mudanças, se houverem
     */
    private function checkChanges(int $product_id, string $newName, int $newCategory, string $newPrice, int $newQtt_stock, int $newStatus_product): ?array
    {
        $search = new Select();
        $currentProduct = $search->selectFirst(self::Table, 'WHERE product_id = :id', "id={$product_id}", 'name, category_id, price, qtt_stock, status_product');

        if (!$currentProduct) {
            $this->message = 'Produto não existente.';
            $this->messageType = ALERT_WARNING;
            return null;
        }

        $changes = [];

        // Verifica e valida o nome
        if ($newName !== $currentProduct['name']) {
            if (strlen($newName) > 100) {
                $this->message = 'O nome do produto não deve ter mais que 100 caracteres.';
                $this->messageType = ALERT_WARNING;
                return null;
            }
            $changes['name'] = $newName;
        }

        // Verifica e valida a categoria
        if ($newCategory !== $currentProduct['category_id']) {
            $validCategory = $search->selectFirst('category', 'WHERE category_id = :id', "id={$newCategory}", 'category_id');
            if (!$validCategory) {
                $this->message = 'Categoria inválida.';
                $this->messageType = ALERT_WARNING;
                return null;
            }
            $changes['category_id'] = $newCategory;
        }

        // Verifica e valida o preço
        if (brl_to_decimal($newPrice) != $currentProduct['price']) {
            if (strlen($newPrice) > 12) {
                $this->message = 'Preço muito elevado para produto.';
                $this->messageType = ALERT_WARNING;
                return null;
            }
            $changes['price'] = $newPrice;
        }

        // Verifica e valida a quantidade em estoque
        if ($newQtt_stock !== $currentProduct['qtt_stock']) {
            if ($newQtt_stock > 0 and $newQtt_stock > 5000) {
                $this->message = 'Quantidade em estoque inválida. Min: 1 | Max: 5000';
                $this->messageType = ALERT_WARNING;
                return null;
            }
            $changes['qtt_stock'] = $newQtt_stock;
        }

        // Verifica e valida o status
        if ($newStatus_product !== $currentProduct['status_product']) {
            if (!in_array($newStatus_product, [0, 1])) {
                $this->message = 'Status do produto inválido.';
                $this->messageType = ALERT_WARNING;
                return null;
            }
            $changes['status_product'] = $newStatus_product;
        }

        if (empty($changes)) {
            $this->message = 'Nenhuma alteração detectada.';
            $this->messageType = ALERT_INFO;
            return null;
        }

        return $changes;
    }

}