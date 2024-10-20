<?php

use Autoload\Core\DB\Select;

$search = new Select();

$bestSellingProducts = $search->executeQuery('
    SELECT 
    p.product_id,
    p.name AS product_name,
    p.price,
    s.name AS seller_name,
    SUM(pr.quantity) AS total_reservations
    FROM product p
    LEFT JOIN product_reserve pr ON p.product_id = pr.product_id
    LEFT JOIN seller s ON p.seller_id = s.seller_id
    WHERE p.status_product = 1 AND status_account = 1 AND licensed = 1 AND p.qtt_stock > 0
    GROUP BY p.product_id, p.name, p.price, s.name
    ORDER BY total_reservations DESC
');

?>

<div class="home">

    <div class="title">
        <h2>Bem vindo, <?= $session->username ?>!</h2>
    </div>

    <?php if (!empty($bestSellingProducts)) { ?>

        <div class="products-tab">
            <h3>Produtos em destaque</h3>

            <ul class="products-show"><!-- Aqui exibe os produtos -->
                <?php foreach ($bestSellingProducts as $product) { ?>
                    <li class="product">
                        <p>
                            <a href="<?= url("app/product/{$product['product_id']}") ?>">
                                <?= $product['product_name'] ?>
                            </a>
                        </p>

                        <p>R$ <?= brl_price_format($product['price']) ?></p>

                        <p>Vendedor: <?= $product['seller_name'] ?></p>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } else { ?>
        <div class="empty-products">
            <h3>Nenhum produto à venda!</h3>
        </div>
    <?php } ?>
</div>