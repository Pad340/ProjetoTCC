<?php

namespace Autoload\Models;

use Autoload\Core\Session;

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
                'quantity' => 1,
                'price' => $product['price']
            ];

            // Atualiza o total com o preço do novo produto
            $this->cart->total += $product['price'];
        }
    }
}