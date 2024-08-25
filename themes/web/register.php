<?php

use Autoload\Models\User;

if (isset($_POST['register'])) {
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        echo 'Confirme sua senha corretamente!';
    } else {
        $user = new User();
        $user->register($_POST['name'], $_POST['email'], $_POST['password']);
        echo $user->getMessage();
    }
}
?>

<div class="form-styling">
    <h1 style="color:grey">Criar Conta</h1>
    <form action="./" method="post" autocomplete="off">
        <!-- <label for="name">Nome:</label> -->
        <input type="text" name="name" id="name" value="<?= $_POST['name'] ?? "" ?>" placeholder="Nome" required />

        <!-- <label for="email">E-mail:</label> -->
        <input type="email" name="email" id="email" value="<?= $_POST['email'] ?? "" ?>" placeholder="E-mail" required />

        <!-- <label for="password">Senha:</label> -->
        <input type="password" name="password" id="password" placeholder="Senha" required />

        <!-- <label for="confirmPassword">Confirme a senha:</label> -->
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirmar Senha" required /> <br>

        <button type="submit" name="register">Cadastrar-se</button>
    </form>

    <p>Já possui uma conta? Faça <a href="<?= url_actual() . '?login' ?>">Login</a></p>
</div>