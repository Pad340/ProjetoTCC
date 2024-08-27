<?php
require_once "../../vendor/autoload.php";

use Autoload\Core\Session;
use Autoload\Models\User;

$session = new Session();

if (!$session->has('authUser')) {
    redirect('../web/');
}


if (isset($_GET['page']) and $_GET['page'] == 'logout') {
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
<?php include 'includes/header.php'; // Inclui o cabeçalho ?>

<main>
    <?php
    // Define a página padrão
    $page = $_GET['page'] ?? 'home';

    // Inclui o conteúdo da página com base no valor do parâmetro 'page'
    $path = "pages/$page.php";

    if (file_exists($path)) {
        include $path;
    } else {
        echo "<p>Página não encontrada.</p>";
    }
    ?>
</main>

<?php include 'includes/footer.php'; // Inclui o rodapé?>
</body>
</html>