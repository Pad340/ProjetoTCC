<h2>Aqui é o cadastro</h2>
<form action="" method="post" autocomplete="off">
    <label for="name">Nome:</label>
    <input type="text" name="name" id="name" required value=""> <br>
    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" required value=""> <br>
    <label for="password">Senha:</label>
    <input type="password" name="password" id="password" required value=""> <br>
    <label for="confirmPassword">Confirme a senha:</label>
    <input type="password" name="confirmPassword" id="confirmPassword" required value=""> <br>
    <button type="submit" name="submit">Cadastrar-se</button>
</form>
<p>Já possui uma conta? Faça <a href="<?= url_actual() . '?login' ?>">Login</a></p>