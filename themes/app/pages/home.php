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
        <h2>Bem-vindo(a), <?= $session->username ?>! 👋</h2>
    </div>

    <?php if (!empty($bestSellingProducts)) { ?>

        <h1>Produtos em destaque</h1>
        <div id="slider">
            <!-- Radio inputs pra navegação do slider -->
            <?php
            $counter = 0;
            foreach ($bestSellingProducts as $product) {
                $counter++;
                if ($counter > 3) break; // Limite de 3 produtos
                echo '<input type="radio" name="slider" id="slide' . $counter . '"' . ($counter === 1 ? ' checked' : '') . '>';
            }
            ?>

            <div id="slides">
                <div id="overflow">
                    <div class="inner">
                        <?php
                        // Reset do contador pra usar as classes no CSS
                        $counter = 0;
                        foreach ($bestSellingProducts as $product) {
                            $counter++;
                            if ($counter > 3) break; // Limite de 3 produtos
                        ?>
                            <div class="slide slide_<?= $counter ?>">
                                <div class="slide-content">
                                    <div class="upper-content">
                                        <div class="product-info-slider">
                                            <h2><?= $product['product_name'] ?></h2>
                                            <p>R$ <?= brl_price_format($product['price']) ?></p>
                                            <p>Vendedor: <?= $product['seller_name'] ?></p>
                                        </div>
                                        <div class="product-image-slider">
                                            <!-- Foto placeholder -->
                                            <img class="product-icon" src="/projetotcc/storage/images/icon_product_PLACEHOLDER.png" alt="product-icon">
                                        </div>
                                    </div>
                                    <div class="lower-content">
                                        <a href="<?= url("app/product/{$product['product_id']}") ?>">Ver produto</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div id="bullets">
                <?php
                // Reset do contador pra criar os bullets
                $counter = 0;
                foreach ($bestSellingProducts as $product) {
                    $counter++;
                    if ($counter > 3) break; // Limite de 3 produtos
                    echo '<label for="slide' . $counter . '"></label>';
                }
                ?>
            </div>
        </div>
</div>

<div class="homepage-content">
    <h1 id="other-products-text">Outros produtos</h1>

    <div class="grid-container">
        <div class="product-grid">
            <?php foreach ($bestSellingProducts as $product) { ?>
                <div class="product-item">
                    <div class="product-image">
                        <img src="/projetotcc/storage/images/icon_product_PLACEHOLDER.png" alt="product-image">
                    </div>
                    <div class="product-info">
                        <h3><?= $product['product_name'] ?></h3>
                        <p>R$ <?= brl_price_format($product['price']) ?></p>
                        <p>Vendedor: <?= $product['seller_name'] ?></p>
                        <a href="<?= url("app/product/{$product['product_id']}") ?>">Ver produto</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php } else { ?>
    <div class="empty-products">
        <h3>Nenhum produto a venda!</h3>
    </div>
<?php } ?>


<!-- 
    Trecho de código não utilizado (n tirei caso for usar dps)
    <?php if (!empty($bestSellingProducts)) { ?>
        <div class="products-tab">
            <ul class="products-show">
                <?php
                $counter = 0;
                foreach ($bestSellingProducts as $product) {
                    $counter++;
                    if ($counter > 3) break; // Limit to 3 products
                ?>
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
-->