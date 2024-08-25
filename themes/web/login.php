<?php

use Autoload\Models\User;

if (isset($_POST['login'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo 'Informe um e-mail válido';
    } else {
        $user = new User();
        $user->login($_POST['email'], $_POST['password']);
        echo $user->getMessage();
    }
}
?>

<div class="form-styling">
    <h1 style="color:grey">Fazer Login</h1>

    <form action="./?login" method="post" autocomplete="off">
        <!-- <label for="email">Informe seu e-mail:</label> -->
        <input type="email" name="email" id="email" placeholder="E-mail" required/>

        <!-- <label for="password">Informe sua senha:</label> -->
        <input type="password" name="password" id="password" placeholder="Senha" required/> </br>

        <button type="submit" name="login">Logar-se</button>
    </form>
    
    <p>Ainda não possui uma conta? Faça o <a href="<?= url_actual() ?>">Cadastro</a></p>
</div>

