<?php

use Autoload\Core\DB\Select;
use Autoload\Models\Seller;

// Espera o click no botão de cadastrar
if (isset($_POST['register_btn'])) {
    $seller = new Seller();
    $seller->register($_POST['seller_name'], $_POST['seller_cpf'], $_POST['phone_number']);

    echo $seller->getMessage();
}

// Espera o click no botão de ativar conta
if (isset($_POST['enable_btn'])) {
    $seller = new Seller();
    $seller->enable();

    echo $seller->getMessage();
    refresh(3);
}

// Espera o click no botão de desativar conta
if (isset($_POST['disable_btn'])) {
    $seller = new Seller();
    $seller->disable();

    echo $seller->getMessage();
    refresh(3);
}

$search = new Select();
$user_result = $search->selectFirst('user', 'WHERE user_id = :id', "id={$session->authUser}", 'name, email, status_account, created_at');
$seller_result = $search->selectFirst('seller', 'WHERE user_id = :id', "id={$session->authUser}", 'name, cpf, phone_number, status_account, licensed');

?>

<h1 id="user-config-page-title">Configurações desta conta</h1>

<!-- UC = User Config -->
<div class="UC-main">
    <div class="user-config-container">
        <h1 id="user-config-text">Dados do usuário</h1>
        <div class="user-config-name">
            <h2>Nome</h2>
            <p><?= $user_result['name'] ?></p>
        </div>
        <div class="user-config-email">
            <h2>E-mail</h2>
            <p><?= $user_result['email'] ?></p>
        </div>
        <div class="user-config-member-since">
            <h2>Membro desde</h2>
            <p><?= date_fmt($user_result['created_at'], 'd/m/Y') ?></p>
        </div>
    </div>


    <!-- Não é vendedor na sessão e não possui conta de vendedor -->
    <?php if (!$session->has('authSeller') and !$seller_result) { ?>
    <div class="seller-form">
        <h1>Criar uma conta de vendedor</h1>
        <form action="" method="post" autocomplete="off">
            <div class="seller-form-name">
                <label for="seller_name">Nome de vendedor ou turma</label>
                <input type="text" id="seller_name" name="seller_name" required/>
            </div>
            <div class="seller-form-cpf">
                <label for="seller_cpf">CPF do vendedor ou responsável pelas vendas da turma</label>
                <input type="text" id="seller_cpf" name="seller_cpf" maxlength="14" required/>
            </div>
            <div class="seller-form-phone">
                <label for="phone_number">Número de telefone do vendedor ou responsável pelas vendas da turma</label>
                <input type="text" id="phone_number" name="phone_number" maxlength="15" required/>
            </div>
            <div class="seller-form-submit-button">
                <button type="submit" name="register_btn">Cadastrar</button>
            </div>
        </form>
    </div>
</div><!-- div pro main fechar -->

    <!-- Não tem autorização para ser vendedor -->
<?php } elseif ($seller_result['licensed'] == 0) { ?>
    </div> <!-- div pro main fechar -->
    <div class="admin-auth-text">
        <h3>Aguarde a autorização de um administrador para vender.</h3>
    </div>

    <!-- Conta desativada -->
<?php } elseif ($seller_result['status_account'] == 0) { ?>
    </div> <!-- div pro main fechar -->
    <div class="seller-activate-button">
        <form action="" method="post">
            <button type="submit" name="enable_btn">Ativar sua conta de vendedor</button>
        </form>
    </div>

    <!-- Conta Ativada -->
<?php } else { ?>
    <div class="seller-config-container">
        <h1 id="seller-config-text">Dados do vendedor</h1>

        <div class="seller-name">
            <h2>Nome do Vendedor ou Turma</h2>
            <p><?= $seller_result['name'] ?></p>
        </div>
        <div class="seller-cpf">
            <h2>CPF</h2>
            <p><span id="seller_cpf"><?= $seller_result['cpf'] ?></span></p>
        </div>
        <div class="seller-phone">
            <h2>Telefone</h2>
            <p><span id="phone_number"><?= $seller_result['phone_number'] ?></span></p>
        </div>
        <div class="seller-deactivate-button">
            <form action="" method="post">
                <button type="submit" name="disable_btn">Desativar conta de vendedor</button>
            </form>
        </div>
    </div>
    </div> <!-- div pro main fechar -->
<?php } ?>


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