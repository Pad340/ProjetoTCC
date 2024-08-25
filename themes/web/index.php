<?php

use Autoload\Core\Session;

require_once "../../vendor/autoload.php";

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Re-IFeitório</title>
    <link rel="stylesheet" href="../../shared/styles/styles.css" />
    <link rel="icon" href="../../storage/images/logoBarra.png">
</head>

<body>

    <div class="whiteLogo">
        <img class="whiteLogo2" src="../../storage/images/whiteLogo.png" height="300">
    </div>

</body>


<?php
if (isset($_GET['login'])) {
    include 'login.php';
} else {
    include 'register.php';
}
$session = new Session();

if ($session->has('authUser')) {
    redirect('../app/');
}
?>
</body>

</html>