<?php
require_once "../../vendor/autoload.php";

use Autoload\Core\Session;
use Autoload\Models\Seller;
use Autoload\Models\User;

$session = new Session();

if (!$session->has('authUser')) {
    redirect('../web/');
}

if (!$session->has('authSeller')) {
    (new Seller())->login();
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
    <script src="../../shared/js/jquery-3.7.1.min.js"></script>
    <script src="../../shared/js/jquery.mask.min.js"></script>
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
        include 'pages/404.php';
    }
    ?>
</main>

<?php include 'includes/footer.php'; // Inclui o rodapé ?>
</body>
</html>