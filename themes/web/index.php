<?php

require_once "../../vendor/autoload.php";

use Autoload\Models\User;

session_start();
if (isset($_POST['submit'])) {
    $user = new User($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirmPassword']);
    $user->register();
    echo $user->getMessage();

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
<?php include "register.php"; ?>
</body>
</html>