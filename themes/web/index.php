<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $SITE_NAME ?></title>
    <link rel="stylesheet" href="../../shared/styles/styles.css">
</head>
<body>
    <h1>Bem vindo ao <?= $SITE_NAME ?></h1>
    <?php
    session_start();
    
    include "login.php";
    ?>
</body>
</html>