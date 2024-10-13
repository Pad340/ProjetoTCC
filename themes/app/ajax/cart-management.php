<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Autoload\Core\DB\Select;
use Autoload\Models\Alert;
use Autoload\Models\Cart;

// Recebe os dados do ajax
$data = json_decode(file_get_contents('php://input'), true);

$search = new Select();
$cart = new Cart();

if (isset($data['product_id'])) {
    $product = $search->selectFirst(
        'product',
        'WHERE product_id = :id AND status_product = 1',
        "id={$data['product_id']}",
        'product_id, name, price, qtt_stock'
    );

    if ($data['action'] == 'add') { // Adiciona um produto ou unidade de um produto ao carrinho e retorna os dados atualizados
        try {
            $cart->add($product);

            foreach ($cart->getCart()->products as $productInCart) {
                if ($productInCart['id'] == $data['product_id']) {
                    echo json_encode([
                        'quantity' => $productInCart['quantity'],
                        'price' => brl_price_format($productInCart['price'] * $productInCart['quantity']),
                        'total' => brl_price_format($cart->getCart()->total)
                    ]);
                }
            }
        } catch (Exception $e) {
            // Retorna uma mensagem de erro se o estoque for insuficiente
            echo json_encode(['error' => $e->getMessage()]);
        }

    } elseif ($data['action'] == 'removeOne') { // Remove uma unidade de um produto do carrinho e retorna os dados atualizados
        $cart->removeOne($data['product_id']);
        foreach ($cart->getCart()->products as $productInCart) {
            if ($productInCart['id'] == $data['product_id']) {
                echo json_encode([
                    'quantity' => $productInCart['quantity'],
                    'price' => brl_price_format($productInCart['price'] * $productInCart['quantity']),
                    'total' => brl_price_format($cart->getCart()->total)
                ]);
            }
        }

    } elseif ($data['action'] == 'remove') { // Remove o produto do carrinho
        $cart->remove($data['product_id']);
    }
} else { // Esvazia o carrinho
    $cart->empty();
}

