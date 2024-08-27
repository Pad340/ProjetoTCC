<?php

use Autoload\Models\User;

if (isset($_POST['login'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo 'Informe um e-mail válido';
    } else {
        $user = new User();
        if ($user->login($_POST['email'], $_POST['password'])) {
            redirect('../app/');
        }
        echo $user->getMessage();
    }
}
?>

<h2>Aqui é o login</h2>

<form action method="post" autocomplete="off">
    <label for="email">Informe seu e-mail:</label>
    <input type="email" name="email" id="email" required/>
    <label for="password">Informe sua senha:</label>
    <input type="password" name="password" id="password" required/>
    <button type="submit" name="login">Logar-se</button>
</form>

<p>Ainda não possui uma conta? Faça o <a href="<?= url('web/register') ?>">Cadastro</a></p>