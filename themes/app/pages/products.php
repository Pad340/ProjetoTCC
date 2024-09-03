<?php

use Autoload\Core\DB\Select;
use Autoload\Models\Product;

if (isset($_POST['product_register_btn'])) {
    $product = new Product();
    $product->register($_POST['name'], $_POST['category'], $_POST['price'], $_POST['qtt_stock']);
    echo $product->getMessage();
}

if (isset($_POST['update_product_btn'])) {
    $product = new Product();
    $product->update($_POST['product_id'], $_POST['name'], $_POST['category'], $_POST['price'], $_POST['qtt_stock'], $_POST['status_product']);
    echo $product->getMessage();
}

$search = new Select();
$categories = $search->selectAll('category', 'WHERE status = :s', 's=1', 'category_id, name');

$products = $search->executeQuery(
    'SELECT p.product_id, p.name, p.category_id, c.name AS category, p.price, p.qtt_stock, p.status_product
            FROM product AS p
            LEFT JOIN category AS c ON c.category_id = p.category_id
            WHERE p.status_product = :sp AND p.seller_id = :id',
    "sp=1&id={$session->authSeller}"
);

?>

<div class="products">
    <?php
    if ($session->has('authSeller')) {
        ?>

        <h1>Seus produtos</h1>

        <div class="product-register">
            <h2>Cadastrar novo produto</h2>

            <form action="" method="post" autocomplete="off">
                <label for="name">Nome do produto:</label>
                <input type="text" name="name" id="name" required>

                <label for="category">Categoria:</label>
                <select name="category" id="category">

                    <option value="0" disabled selected>Escolha uma categoria</option>
                    <?php
                    foreach ($categories as $category) {
                        ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                        <?php
                    }
                    ?>

                </select>

                <label for="price">Preço:</label>
                <input type="text" name="price" id="price" placeholder="0,00" maxlength="10" required>

                <label for="qtt_stock">Quantidade em estoque:</label>
                <input type="number" name="qtt_stock" id="qtt_stock" required>

                <button type="submit" name="product_register_btn">Cadastrar produto</button>
            </form>
        </div>

        <?php
        if ($products) {
            ?>

            <div class="product-list">
                <h2>Produtos cadastrados</h2>

                <table>
                    <tr>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Em estoque</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    <?php
                    foreach ($products as $product) {
                        ?>
                        <tr>
                            <td><?= $product['name'] ?></td>
                            <td><?= $product['category'] ?></td>
                            <td><?= brl_price_format($product['price']) ?></td>
                            <td><?= $product['qtt_stock'] ?></td>
                            <td><?= $product['status_product'] == 1 ? 'Habilitado' : 'Desabilitado' ?></td>
                            <td>
                                <a href="javascript:void(0);"
                                   onclick='openModal(<?= json_encode($product) ?>)'>Editar</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>

            <style>
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgb(0, 0, 0);
                    background-color: rgba(0, 0, 0, 0.4);
                }

                .modal-content {
                    background-color: #fefefe;
                    margin: 15% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 80%;
                    border-radius: 10px;
                }

                .close {
                    color: #aaa;
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                }

                .close:hover,
                .close:focus {
                    color: black;
                    text-decoration: none;
                    cursor: pointer;
                }
            </style>

            <div id="editModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Editar Produto</h2>
                    <form action="" method="post">
                        <input type="hidden" name="product_id" id="edit_product_id">
                        <label for="edit_name">Nome do produto:</label>
                        <input type="text" name="name" id="edit_name" required>

                        <label for="edit_category">Categoria:</label>
                        <select name="category" id="edit_category">
                            <option value="0" disabled selected>Escolha uma categoria</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                            <?php } ?>
                        </select>

                        <label for="edit_price">Preço:</label>
                        <input type="text" name="price" id="edit_price" required>

                        <label for="edit_qtt_stock">Quantidade em estoque:</label>
                        <input type="number" name="qtt_stock" id="edit_qtt_stock" required>

                        <label for="edit_status_product">Desativar produto?</label>
                        <select name="status_product" id="edit_status_product">
                            <option value="1">Não</option>
                            <option value="0">Sim</option>
                        </select>

                        <button type="submit" name="update_product_btn">Salvar alterações</button>
                    </form>
                </div>
            </div>

            <?php
        }
    }
    ?>
    <script>
        function applyPriceMask(inputId) {
            document.getElementById(inputId).addEventListener('input', function (e) {
                let value = e.target.value;

                // Remove qualquer caractere que não seja número
                value = value.replace(/\D/g, '');

                // Adiciona a vírgula antes dos últimos dois dígitos
                value = (value / 100).toFixed(2).replace('.', ',');

                // Atualiza o valor do input com a máscara
                e.target.value = value;
            });
        }

        function applyStockValidation(inputId, min = 0, max = 5000) {
            document.getElementById(inputId).addEventListener('input', function (e) {
                let value = e.target.value;

                // Remove qualquer caractere que não seja número
                value = value.replace(/\D/g, '');

                // Garante que o valor seja maior ou igual ao mínimo e não maior que o máximo
                if (value < min) {
                    e.target.value = min;
                } else if (value > max) {
                    e.target.value = max;
                } else {
                    e.target.value = value;
                }
            });
        }

        applyPriceMask('price');
        applyPriceMask('edit_price');
        applyStockValidation('qtt_stock');
        applyStockValidation('edit_qtt_stock');

        // Função para abrir o modal
        function openModal(product) {

            // Preencher o formulário do modal com os dados do produto
            document.getElementById('edit_product_id').value = product.product_id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_category').value = product.category_id;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_qtt_stock').value = product.qtt_stock;

            // Mostrar o modal
            document.getElementById('editModal').style.display = "block";
        }

        // Fechar o modal
        document.querySelector('.close').onclick = function () {
            document.getElementById('editModal').style.display = "none";
        }

        // Fechar o modal ao clicar fora do conteúdo
        window.onclick = function (event) {
            if (event.target === document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = "none";
            }
        }
    </script>
</div>
