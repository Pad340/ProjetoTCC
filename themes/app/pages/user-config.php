<?php

use Autoload\Core\DB\Select;
use Autoload\Models\Seller;

if (isset($_POST['register_btn'])) {
    $seller = new Seller();
    $seller->register($_POST['seller_name'], $_POST['seller_cpf'], $_POST['phone_number']);

    echo $seller->getMessage();
}

if (isset($_POST['enable_btn'])) {
    $seller = new Seller();
    $seller->enable();

    echo $seller->getMessage();
}

if (isset($_POST['disable_btn'])) {
    $seller = new Seller();
    $seller->disable();

    echo $seller->getMessage();
}

$search = new Select();
$user_result = $search->selectFirst('user', 'WHERE user_id = :id', "id={$session->authUser}", 'name, email, status_account, created_at');
$seller_result = $search->selectFirst('seller', 'WHERE user_id = :id', "id={$session->authUser}", 'name, cpf, phone_number, status_account');

?>
<div class="user-config">
    <h1>Configurações da conta</h1>

    <p>Nome: <?= $user_result['name'] ?></p>
    <p>E-mail: <?= $user_result['email'] ?></p>
    <p>Membro desde: <?= date_fmt($user_result['created_at'], 'd/m/Y') ?></p>

    <?php
    if (!$session->has('authSeller') and !$seller_result) {
        ?>

        <div class="seller-form">
            <h2>Criar uma conta de vendedor</h2>
            <form action="" method="post" autocomplete="off">
                <label for="seller_name">Nome de vendedor ou turma:</label>
                <input type="text" name="seller_name" id="seller_name" required/>

                <label for="seller_cpf">CPF do vendedor ou responsável pelas vendas da turma:</label>
                <input type="text" name="seller_cpf" id="seller_cpf" maxlength="14" required/>

                <label for="phone_number">Número de telefone do vendedor ou responsável pelas vendas da turma:</label>
                <input type="text" name="phone_number" id="phone_number" maxlength="15" required/>

                <button type="submit" name="register_btn">Cadastrar</button>
            </form>
        </div>

        <?php
    } elseif ($seller_result['status_account'] == 0) {
        ?>

        <form action="" method="post">
            <button type="submit" name="enable_btn">Reativar conta de vendedor</button>
        </form>

        <?php
    } else {
        ?>

        <p>Vendedor: <?= $seller_result['name'] ?></p>
        <p>CPF: <span id="seller_cpf"><?= $seller_result['cpf'] ?></span></p>
        <p>Telefone: <span id="phone_number"><?= $seller_result['phone_number'] ?></span></p>

        <form action="" method="post">
            <button type="submit" name="disable_btn">Desativar conta de vendedor</button>
        </form>

        <?php
    }
    ?>
    <script>
        $(document).ready(function () {
            // Máscara para CPF
            $('#seller_cpf').mask('000.000.000-00');

            // Máscara para o número de telefone
            const phoneMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                phoneOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(phoneMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone_number').mask(phoneMaskBehavior, phoneOptions);
        });
    </script>
</div>