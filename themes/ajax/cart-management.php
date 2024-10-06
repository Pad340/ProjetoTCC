<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Autoload\Core\DB\Select;
use Autoload\Models\Cart;

$productID = json_decode(file_get_contents('php://input'), true)['product_id'];

$search = new Select();
$product = $search->selectFirst(
    'product',
    'WHERE product_id = :id AND status_product = 1',
    "id={$productID}",
    'product_id, price'
);

if (empty($product)) {
    echo 'Produto inválido!';
    //(new \Autoload\Models\Alert('Produto inválido!', ALERT_ERROR))
}

$cart = new Cart();
$cart->add($product);