<?php

use Autoload\Core\DB\Select;
use Autoload\Models\Product;

// Verifica se o usuário é um vendedor
if (!$session->has('authSeller')) {
    redirect('../app');
}

// Espera a ação de cadastrar um produto
if (isset($_POST['product_register_btn'])) {
    $product = new Product();
    $product->register($_POST['name'], $_POST['category'], $_POST['price'], $_POST['qtt_stock']);
    echo $product->getMessage();
}

// Espera a ação de atualizar um produto
if (isset($_POST['update_product_btn'])) {
    $product = new Product();
    $product->update($_POST['product_id'], $_POST['edit_category'], $_POST['edit_price'], $_POST['edit_qtt_stock'], $_POST['edit_status_product']);
    echo $product->getMessage();
}

$search = new Select();
$categories = $search->selectAll('category', 'WHERE status = :s', 's=1', 'category_id, name');

$products = $search->executeQuery(
    'SELECT
    p.product_id,
    p.name,
    p.category_id,
    c.name AS category,
    p.price,
    p.qtt_stock,
    p.status_product
    FROM product p
    LEFT JOIN category c ON c.category_id = p.category_id
    WHERE p.seller_id = :id',
    "id={$session->authSeller}"
);

?>

<div class="products">
    <h1>Gerenciar seus produtos</h1>

    <table class="products-table">
        <thead>
        <tr>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Em estoque</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <!-- Botão pra abrir o modal de add -->
            <td class="table-add-button" colspan="6" onclick='openCreateModal()'>
                <a class="add-product-button" href="javascript:void(0);">
                    Cadastrar novo produto
                    <img class="product-add-icon" src="/projetotcc/storage/images/icon_addProduct.png"
                         alt="add-product-icon">
                </a>
            </td>
        </tr>

        <?php
        if ($products) {
            foreach ($products as $product) {
                $product['price'] = brl_price_format($product['price']);
                ?>
                <tr>
                    <td><?= $product['name'] ?></td>
                    <td><?= $product['category'] ?></td>
                    <td>R$ <?= $product['price'] ?></td>
                    <td><?= $product['qtt_stock'] ?></td>
                    <td <?= $product['status_product'] != 1 ? 'style="color: red"' : '' ?>>
                        <?= $product['status_product'] == 1 ? 'Habilitado' : 'Desabilitado' ?>
                    </td>

                    <!-- Botão de edit -->
                    <td>
                        <a class="edit-button" href="javascript:void(0);"
                           onclick='openEditModal(<?= json_encode($product) ?>)'>
                            Editar
                            <img class="edit-icon-div" src="/projetotcc/storage/images/icon_edit.png" alt="edit-icon">
                        </a>
                    </td>
                </tr>
            <?php }
        } ?>

        </tbody>
    </table>

    <!-- Modal Edit -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Produto</h2>
            <form action="" method="post">
                <input type="hidden" name="product_id" id="edit_product_id">

                <label for="edit_name">Nome do produto</label>
                <input type="text" name="edit_name" id="edit_name" required disabled>

                <label for="edit_category">Categoria</label>
                <select name="edit_category" id="edit_category">
                    <option value="0" disabled selected>Escolha uma categoria</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                    <?php } ?>
                </select>

                <label for="edit_price">Preço</label>
                <input type="text" name="edit_price" id="edit_price" maxlength="10" required>

                <div class="inline-group">
                    <div class="amount-in-stock-div">
                        <label for="edit_qtt_stock">Quantidade em estoque</label> <br>
                        <input type="number" name="edit_qtt_stock" id="edit_qtt_stock" required>
                    </div>
                    <div class="product-deactivate-div">
                        <label for="edit_status_product">Desativar produto?</label> <br>
                        <select name="edit_status_product" id="edit_status_product">
                            <option value="1">Não</option>
                            <option value="0">Sim</option>
                        </select>
                    </div>
                </div>

                <button class="edit-submit-button-div" type="submit" name="update_product_btn">Salvar
                    alterações
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Create -->
    <div id="createModal" class="modal">
        <div class="create-modal-content">
            <span class="close">&times;</span>
            <h2>Cadastrar novo produto</h2>

            <form action="" method="post" autocomplete="off">
                <label for="name">Nome do produto:</label>
                <input type="text" name="name" id="name" required>

                <label for="category">Categoria:</label>
                <select name="category" id="category" required>

                    <option value="0" hidden selected>Escolha uma categoria</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
                    <?php } ?>

                </select>

                <label for="price">Preço:</label>
                <input type="text" name="price" id="price" placeholder="0,00" maxlength="10" required>

                <label for="qtt_stock">Quantidade em estoque:</label>
                <input type="number" name="qtt_stock" id="qtt_stock" required>

                <button class="create-submit-button-div" type="submit" name="product_register_btn">
                    Cadastrar produto
                </button>
            </form>
        </div>
    </div>

    <script>
        /**
         * Aplica uma máscara no preço do produto
         * @param inputId
         */
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

        /**
         * Aplica uma máscara ao estoque
         * @param inputId
         * @param min
         * @param max
         */
        function applyStockValidation(inputId, min = 0, max = 5000) {
            document.getElementById(inputId).addEventListener('input', function (e) {
                let value = e.target.value;

                // Remove qualquer caractere que não seja número
                value = value.replace(/\D/g, '');

                value = Math.max(min, Math.min(max, parseInt(value) || 0));

                e.target.value = value;
            });
        }

        applyPriceMask('price');
        applyStockValidation('qtt_stock');

        /**
         * Abre o modal de editar produto
         * @param product
         */
        function openEditModal(product) {

            // Preenche o formulário do modal com os dados do produto
            document.getElementById('edit_product_id').value = product.product_id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_category').value = product.category_id;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_qtt_stock').value = product.qtt_stock;

            // Ajustar o label e as opções de ativar/desativar produto com base no status atual
            const statusLabel = document.querySelector('label[for="edit_status_product"]');
            const statusSelect = document.getElementById('edit_status_product');

            if (product.status_product === 0) { // Produto está desativado
                statusLabel.textContent = 'Ativar produto?';
                statusSelect.innerHTML = `
                <option value="0" selected>Não</option>
                <option value="1">Sim</option>
                `;
            } else { // Produto está ativo
                statusLabel.textContent = 'Desativar produto?';
                statusSelect.innerHTML = `
                <option value="1" selected>Não</option>
                <option value="0">Sim</option>
                `;
            }

            applyPriceMask('edit_price');
            applyStockValidation('edit_qtt_stock');

            document.getElementById('editModal').style.display = "block";
        }

        /**
         * Abre modal de cadastro de produto
         */
        function openCreateModal(product) {
            document.getElementById('createModal').style.display = "block";
        }

        /**
         * Fecha o modal
         * @param modalId
         */
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }

        // Fecha os modais ao clicar no botão de fechar
        document.querySelectorAll('.close').forEach(function (closeButton) {
            closeButton.onclick = function () {
                closeModal('editModal');
                closeModal('createModal');
            }
        });

        // Fechar os modais ao clicar fora do conteúdo
        window.onclick = function (event) {
            if (event.target === document.getElementById('editModal')) {
                closeModal('editModal');
            }
            if (event.target === document.getElementById('createModal')) {
                closeModal('createModal');
            }
        };
    </script>
</div>