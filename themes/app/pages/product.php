<?php

use Autoload\Core\DB\Select;

$productID = filter_input(INPUT_GET, 'product_id', FILTER_SANITIZE_NUMBER_INT);

$search = new Select();
$result = $search->executeQuery(
    'SELECT p.name, c.name as category, p.price, p.qtt_stock
            FROM product p
            LEFT JOIN category c ON p.category_id = c.category_id
            WHERE p.product_id = :id AND p.status_product = 1
            LIMIT 1', "id={$productID}");

if (empty($result)) {
    redirect('../home');
}

$product = $result[0];

?>

<div class="product-page">
    <div class="product">

        <div class="product_data">
            <p><?= $product['name'] ?></p>
            <p>Categoria: <?= $product['category'] ?></p>
            <p>Por apenas: R$ <?= brl_price_format($product['price']) ?></p>
        </div>

        <br><!-- Tira isso depois quando for estilizar -->

        <div class="product-addToCart">
            <p>Quantidade em estoque: <?= $product['qtt_stock'] ?></p>

            <?php if ($product['qtt_stock'] > 0) { ?>
                <button onclick="addToCart('<?= $productID ?>')">Adicionar ao carrinho</button>
            <?php } else { ?>
                <p>Produto indisponível para compra.</p>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    /**
     * Adiciona o produto ao carrinho
     * @param product_id
     */
    function addToCart(product_id) {
        $.ajax({
            url: "../ajax/cart-management.php",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                'product_id': product_id,
                'action': 'add'
            }),
            success: function (data) {
                data = JSON.parse(data);

                if (!document.getElementById("notification")) {
                    // Insere o HTML do alerta no topo da página
                    document.body.insertAdjacentHTML('afterbegin', data.alert);
                }

                const notification = document.getElementById("notification");

                // Mostrar a notificação após um pequeno atraso para animação
                setTimeout(function() {
                    notification.classList.add("show");
                }, 100); // 100ms para garantir que o DOM está pronto

                // Ocultar a notificação automaticamente
                setTimeout(function() {
                    notification.classList.remove("show");
                }, 2000);
            }
        });
    }
</script>

