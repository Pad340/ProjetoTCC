<?php

use Autoload\Core\Session;

require_once "../../vendor/autoload.php";

$session = new Session();

if ($session->has('authUser')) {
    redirect('../app/');
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-IFeitório</title>
    <link rel="stylesheet" href="../../shared/styles/unloggedPages.css"/>
    <link rel="icon" href="../../storage/images/logoBarra.png"/>
</head>

<body>

<div class="whiteLogo">
    <img class="whiteLogo2" src="../../storage/images/whiteLogo.png" alt="Logo" height="300">
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
        echo "<p>Página não encontrada.</p>";
    }
    ?>
</main>

</body>
</html>
