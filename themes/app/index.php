<?php

use Autoload\Core\Session;
use Autoload\Models\User;

require_once "../../vendor/autoload.php";

$session = new Session();

if (!$session->has('authUser')) {
    redirect('../web/');
}

if (isset($_GET['logout'])) {
    (new User())->logout();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-IFeitório | App</title>
    <link rel="stylesheet" href="../../shared/styles/styles.css">
</head>
<body>
<h1>Área logada</h1>
<a href="?logout">Logout</a>
</body>
</html>