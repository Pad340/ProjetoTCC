<?php
// Iniciar a sessão
session_start();

include 'vendor/autoload.php';

// Configurações de erro (desativar em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto TCC</title>
    <link rel="stylesheet" href="shared/styles/styles.css">
</head>
<body>
<?php include 'shared/views/header.php'; ?>

<h1>Bem-vindo ao meu TCC</h1>
<p>Esta é a página inicial do meu projeto de TCC.</p>

<?php include 'shared/views/footer.php'; ?>
</body>
</html>