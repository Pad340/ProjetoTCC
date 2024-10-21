<?php

use Autoload\Core\Session;

require_once "../../vendor/autoload.php";

$session = new Session();

if ($session->has('authUser')) {
    redirect('../app/');
}
?>

<!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/projetotcc/shared/styles/unloggedPages.css"/>
    <link rel="icon" href="/projetotcc/storage/images/icon_web.png"/>
</head>

<body>

<div class="whiteLogo">
    <img class="whiteLogo2" src="/projetotcc/storage/images/logo_white.png" alt="Logo" height="300">
</div>

<main>
    <?php
    // Define a página padrão
    $page = $_GET['page'] ?? 'login';

    // Inclui o conteúdo da página com base no valor do parâmetro 'page'
    $path = "pages/$page.php";

    if (file_exists($path)) {
        include $path;
    } else {
        redirect('/projetotcc/themes/404.php');
    }
    ?>
</main>

</body>
</html>
