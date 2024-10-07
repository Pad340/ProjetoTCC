<?php

use Autoload\Core\DB\Select;
use Autoload\Models\Cart;

$cart = (new Cart())->getCart();
$search = new Select();

?>

<div class="cart-page">
    <h2>Carrinho</h2>

    <ul class="cart">
        <?php if (empty($cart->products)) { ?>
            <li class="empty-cart">Nenhum produto no carrinho.</li>
        <?php } else { // Exibe os produtos do carrinho ?>
            <?php foreach ($cart->products as $product) { ?>
                <li class="product-in-cart">
                    <p>
                        <a href="<?= url("app/product/{$product['id']}") ?>">
                            <?= $product['name'] ?><!-- Nome do produto e link para página deste produto -->
                        </a>
                    </p>
                    <p>
                        Quantidade:
                        <!-- Retirar uma unidade -->
                        <span class="cart-less" onclick="removeOne(<?= $product['id'] ?>)"> < </span>
                        <!-- Quantidade -->
                        <span id="quantity-<?= $product['id'] ?>"><?= $product['quantity'] ?></span>
                        <!-- Adicionar uma unidade -->
                        <span class="cart-more" onclick="more(<?= $product['id'] ?>)"> > </span>

                    </p>
                    <p>R$
                        <span id="product-<?= $product['id'] ?>-total"><!-- Preço total das unidades -->
                            <?= brl_price_format($product['price'] * $product['quantity']) ?>
                        </span>
                    </p>
                    <!-- Remover todas as unidades de um produto -->
                    <button onclick="remove(<?= $product['id'] ?>)">Remover</button>
                </li>
            <?php } ?>
            <li class="empty-cart-btn">
                <button onclick="empty()">Esvaziar carrinho</button><!-- Botão de esvaziar carrinho -->
            </li>
        <?php } ?>
        <li class="cart-total"><!-- Total do carrinho -->
            <p>Total: R$ <span id="cart-total"><?= brl_price_format($cart->total) ?></span></p>
        </li>
    </ul>
</div>

<script>
    /**
     * Remove uma unidade do produto
     * @param product_id
     */
    function removeOne(product_id) {
        $.ajax({
            url: "ajax/cart-management.php",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                'product_id': product_id,
                'action': 'removeOne'
            }),
            success: function (data) {
                if (data === '') {
                    window.location.reload();
                }
                data = JSON.parse(data);

                document.getElementById('quantity-' + product_id).innerHTML = data.quantity;
                document.getElementById('product-' + product_id + '-total').innerHTML = data.price;
                document.getElementById('cart-total').innerHTML = data.total;
            }
        });
    }

    /**
     * Adiciona uma unidade ao produto
     * @param product_id
     */
    function more(product_id) {
        $.ajax({
            url: "ajax/cart-management.php",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                'product_id': product_id,
                'action': 'add'
            }),
            success: function (data) {
                data = JSON.parse(data);

                document.getElementById('quantity-' + product_id).innerHTML = data.quantity;
                document.getElementById('product-' + product_id + '-total').innerHTML = data.price;
                document.getElementById('cart-total').innerHTML = data.total;
            }
        });
    }

    /**
     * Remove o produto do carrinho
     * @param product_id
     */
    function remove(product_id) {
        $.ajax({
            url: "ajax/cart-management.php",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                'product_id': product_id,
                'action': 'remove'
            }),
            success: function () {
                window.location.reload();
            }
        });
    }

    /**
     * Esvazia o carrinho
     */
    function empty() {
        $.ajax({
            url: "ajax/cart-management.php",
            type: 'POST',
            contentType: 'application/json',

            success: function () {
                window.location.reload();
            }
        });
    }
</script>