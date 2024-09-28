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
    INNER JOIN product_reserve pr ON p.product_id = pr.product_id
    INNER JOIN seller s ON p.seller_id = s.seller_id
    GROUP BY p.product_id, p.name, p.price, s.name
    ORDER BY total_reservations DESC
');

if (isset($_POST['search_submit'])) {

}

?>

<div class="home">

    <div class="title">
        <h2><?= SITE_TITLE ?></h2>
    </div>

    <div class="search-bar">

        <input type="text" id="search-input" placeholder="Digite sua pesquisa..." oninput="showOptions()">

        <button type="submit" id="search-bar-btn" onclick="search('all')">
            <img src="../../storage/images/loupeIcon.png" alt="Buscar" width="32">
        </button>

        <div id="search-options" class="search-options">
            <div class="option" onclick="search('sellers')">Buscar <span id="search-text"> </span> em Vendedores</div>
            <div class="option" onclick="search('products')">Buscar <span id="search-text-2"> </span> em Produtos</div>
        </div>

    </div>

    <?php if (isset($bestSellingProducts)) { ?>
        <div class="products-tab">
            <h3>Produtos em destaque</h3>

            <div class="product-show">
                <?php foreach ($bestSellingProducts as $product) { ?>
                    <div class="product">
                        nome: <?= $product['product_name'] ?>
                    </div>
                <?php } ?>
            </div>

            <button>Ver tudo</button>
        </div>
    <?php } ?>

    <div class="sellers-tab">
        Alguns vendedores igual como é o ifood
        <button>Buscar mais</button>
    </div>

</div>

<style>
    .search-bar {
        position: relative;
        width: 300px;
    }

    #search-input {
        width: 80%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .search-options {
        display: none;
        position: absolute;
        width: 100%;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 4px;
        border: 1px solid #ccc;
        margin-top: 5px;
        z-index: 1000;
    }

    .option {
        padding: 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .option:hover {
        background-color: #f0f0f0;
    }
</style>

<script>
    function showOptions() {
        let searchText = document.getElementById('search-input').value;
        if (searchText) {
            document.getElementById('search-options').style.display = 'block';
            document.getElementById('search-text').innerText = searchText;
            document.getElementById('search-text-2').innerText = searchText;
        } else {
            document.getElementById('search-options').style.display = 'none';
        }
    }

    function search(type) {
        let searchText = document.getElementById('search-input').value;
        if (searchText) {
            window.location.href = `/search?query=${searchText}&type=${type}`;
        }
    }
</script>

