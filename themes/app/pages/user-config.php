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
<!-- UC = User Config -->
<div class="UC-main">
    <div class="UC-container">
        <div class="UC-info">
            <h1 id="UC-text">Configurações da conta</h1>
            <div class="UC-text-div">
                <h2>Nome</h2>
                <p><?= $user_result['name'] ?></p>
            </div>
            <div class="UC-text-div">
                <h2>E-mail</h2>
                <p><?= $user_result['email'] ?></p>
            </div>
            <div class="UC-member-div">
                <h2>Membro desde</h2>
                <p><?= date_fmt($user_result['created_at'], 'd/m/Y') ?></p>
            </div>
        </div>
    </div>
    <?php
    if (!$session->has('authSeller') and !$seller_result) {
    ?>
        <div class="SC-container">
            <div class="seller-form">
                <h1 id="UC-text">Criar uma conta de vendedor</h1>
                <form action="" method="post" autocomplete="off">
                    <label for="seller_name">Nome de vendedor ou turma</label> <br>
                    <input type="text" name="seller_name" id="seller_name" required /> <br>

                    <label for="seller_cpf">CPF do vendedor ou responsável pelas vendas da turma</label> <br>
                    <input type="text" name="seller_cpf" id="seller_cpf" maxlength="14" required /> <br>

                    <label for="phone_number">Número de telefone do vendedor ou responsável pelas vendas da turma</label> <br>
                    <input type="text" name="phone_number" id="phone_number" maxlength="15" required /> <br>

                    <button id="UC-button" type="submit" name="register_btn">Cadastrar</button>
                </form>
            </div>
        </div>
    <?php
    } elseif ($seller_result['status_account'] == 0) {
    ?>

        <form action="" method="post">
            <button id="UC-button" type="submit" name="enable_btn">Reativar conta de vendedor</button>
        </form>

    <?php
    } else {
    ?>
        <div class="SC-container">
            <div class="UC-info">
                <h1 id="UC-text">Configurações do vendedor</h1>
                <div class="UC-text-div">
                    <h2>Nome do Vendedor</h2>
                    <p><?= $seller_result['name'] ?></p>
                </div>
                <div class="UC-text-div">
                    <h2>CPF</h2>
                    <p><span id="seller_cpf"><?= $seller_result['cpf'] ?></span></p>
                </div>
                <div class="UC-text-div">
                    <h2>Telefone</h2>
                    <p><span id="phone_number"><?= $seller_result['phone_number'] ?></span></p>
                </div>
            </div>
            <form action="" method="post">
                <button id="UC-button" type="submit" name="disable_btn">Desativar conta de vendedor</button>
            </form>
        </div>
    <?php
    }
    ?>
    <script>
        $(document).ready(function() {
            // Máscara para CPF
            $('#seller_cpf').mask('000.000.000-00');

            // Máscara para o número de telefone
            const phoneMaskBehavior = function(val) {
                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                },
                phoneOptions = {
                    onKeyPress: function(val, e, field, options) {
                        field.mask(phoneMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone_number').mask(phoneMaskBehavior, phoneOptions);
        });
    </script>
</div>