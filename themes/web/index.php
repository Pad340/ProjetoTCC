<?php

require_once "../../vendor/autoload.php";

use Autoload\Models\User;

if (isset($_POST['submit'])) {
    $user = new User();
    if ($_POST['password'] !== $_POST['confirmPassword']) {
        echo 'Confirme sua senha corretamente!';
    } else {
        $user->register($_POST['name'], $_POST['email'], $_POST['password']);
        echo $user->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-IFeitório</title>
    <link rel="stylesheet" href="../../shared/styles/styles.css">
</head>
<body>
<h1>Bem vindo ao website</h1>
<?php
if (isset($_GET['login'])) {
    include 'login.php';
} else {
    include 'register.php';
}
?>
</body>
</html>