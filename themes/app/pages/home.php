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
    WHERE p.status_product = 1 AND status_account = 1 AND licensed = 1
    GROUP BY p.product_id, p.name, p.price, s.name
    ORDER BY total_reservations DESC
');

?>



<div class="home">

    <div id="slider">
        <input type="radio" name="slider" id="slide1" checked>
        <input type="radio" name="slider" id="slide2">
        <input type="radio" name="slider" id="slide3">
        <input type="radio" name="slider" id="slide4">
        <div id="slides">
            <div id="overflow">
                <div class="inner">
                    <div class="slide slide_1">
                        <div class="slide-content">
                            <h2>Slide 1</h2>
                        </div>
                    </div>
                    <div class="slide slide_2">
                        <div class="slide-content">
                            <h2>Slide 2</h2>
                        </div>
                    </div>
                    <div class="slide slide_3">
                        <div class="slide-content">
                            <h2>Slide 3</h2>
                        </div>
                    </div>
                    <div class="slide slide_4">
                        <div class="slide-content">
                            <h2>Slide 4</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="bullets">
            <label for="slide1"></label>
            <label for="slide2"></label>
            <label for="slide3"></label>
            <label for="slide4"></label>
        </div>
    </div>


    <div class="title">
        <h2>Bem vindo <?= $session->username ?>!</h2>
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
            <h3>Nenhum produto a venda!</h3>
        </div>
    <?php } ?>
</div>