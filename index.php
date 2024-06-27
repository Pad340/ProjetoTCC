<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto TCC</title>
</head>
<body>
<?php

include 'vendor/autoload.php';

// Configurações de erro (desativar em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$url = (isset($_GET['url'])) ? $_GET['url'] : 'home.php';
$url = array_filter(explode('/', $url));

$file = $url[0] . '.php';

if (is_file($file)) {
    include $file;
} else {
    echo 'erro';
}
?>
</body>
</html>