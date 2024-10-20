<?php

namespace Autoload\Models;

use Autoload\Core\Session;
use Exception;

/**
 * Gerencia os produtos da carrinho
 */
class Cart
{
    private object $cart;

    /**
     * Constructor
     */
    public function __construct()
    {
        $session = new Session();

        if (!$session->has('cart')) {
            $session->set('cart', (object)[
                'products' => [],
                'total' => 0
            ]);
        }

        $this->cart = $session->cart;
    }

    /**
     * Adiciona produtos ao carrinho
     * @param array $product Contendo id, price e quantity
     * @return void
     * @throws Exception
     */
    public function add(array $product): void
    {
        $inCart = false;

        foreach ($this->getCart()->products as &$productInCart) {
            if ($productInCart['id'] === $product['product_id']) {
                // Verifica se a quantidade no carrinho excede o estoque disponível
                $newQuantity = $productInCart['quantity'] + 1;
                if ($newQuantity > $product['qtt_stock']) {
                    // Se a quantidade excede o estoque, não permite adicionar
                    throw new Exception('Estoque insuficiente para o produto ' . $product['name'] . '!');
                }

                // Atualiza a quantidade no carrinho
                $productInCart['quantity'] = $newQuantity;
                $this->updateTotal();
                $inCart = true;
                break;
            }
        }

        // Se o produto não está no carrinho, adiciona
        if (!$inCart) {
            if ($product['qtt_stock'] > 0) { // Só adiciona se houver estoque disponível
                $this->cart->products[] = [
                    'id' => $product['product_id'],
                    'name' => $product['name'],
                    'quantity' => 1,
                    'price' => $product['price']
                ];

                $this->updateTotal();
            } else {
                throw new Exception('Produto sem estoque: ' . $product['name']);
            }
        }
    }

    /**
     * Remove uma unidade de um produto do carrinho
     * @param int $productID ID do produto que será removido
     * @return void
     */
    public function removeOne(int $productID): void
    {
        foreach ($this->cart->products as $index => $productInCart) {
            if ($productInCart['id'] === $productID) {
                $this->cart->products[$index]['quantity'] -= 1;
                $this->cart->total -= $productInCart['price'];

                if ($this->cart->products[$index]['quantity'] <= 0) {
                    unset($this->cart->products[$index]);
                }
                break;
            }
        }
    }

    /**
     * Remove todas as unidade de um produto do carrinho
     * @param int $productID
     * @return void
     */
    public function remove(int $productID): void
    {
        foreach ($this->cart->products as $index => $productInCart) {
            if ($productInCart['id'] === $productID) {
                $this->cart->total -= $productInCart['price'] * $productInCart['quantity'];
                unset($this->cart->products[$index]);
            }
        }
    }

    /**
     * Esvazia o carrinho
     * @return void
     */
    public function empty(): void
    {
        $session = new Session();
        $session->unset('cart');
    }

    /**
     * Retorna o carrinho
     * @return object
     */
    public function getCart(): object
    {
        return $this->cart;
    }

    #####################
    ## Private Methods ##
    #####################

    private function updateTotal(): void
    {
        $total = 0;
        foreach ($this->getCart()->products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        $_SESSION['cart']->total = $total;
    }
}