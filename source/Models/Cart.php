<?php

namespace Autoload\Models;

use Autoload\Core\Session;

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
     */
    public function add(array $product): void
    {
        $inCart = false;

        foreach ($this->cart->products as &$productInCart) {
            if (isset($productInCart['id']) && $productInCart['id'] === $product['product_id']) {
                // Se o produto já está no carrinho, aumenta a quantidade
                $productInCart['quantity'] += 1;

                // Atualiza o total com base no preço do produto
                $this->cart->total += $product['price'];
                $inCart = true;
                break;
            }
        }

        if (!$inCart) {
            $this->cart->products[] = [
                'id' => $product['product_id'],
                'name' => $product['name'],
                'quantity' => 1,
                'price' => $product['price']
            ];

            // Atualiza o total com o preço do novo produto
            $this->cart->total += $product['price'];
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
}