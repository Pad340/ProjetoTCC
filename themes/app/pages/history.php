<?php

use Autoload\Core\DB\Select;

$search = new Select();
$reports = $search->executeQuery('
SELECT r.reserve_id, p.name as product_name, pr.quantity, pr.total_value as value, r.user_id, r.reserved_at, r.total_value, r.redeemed
FROM product_reserve pr
LEFT JOIN product p ON p.product_id = pr.product_id
LEFT JOIN reserve r ON pr.reserve_id = r.reserve_id
WHERE r.user_id = :user_id
ORDER BY reserved_at DESC', "user_id={$session->authUser}");

$lastDate = null;
$lastReserve = null;
?>

<div class="sales_report">
    <h1>Minhas reservas!</h1>

    <?php if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto comprado!</p>

    <?php } else { ?>

        <?php foreach ($reports as $report) { ?>

            <?php if ($lastDate != date_fmt($report['reserved_at'], 'd/m/Y')) {
                $lastDate = date_fmt($report['reserved_at'], 'd/m/Y'); ?>

                <div class="date">
                    <h2>Reservas do dia: <?= $lastDate ?></h2>
                </div>
            <?php } ?>

                <?php if ($lastReserve != $report['reserve_id']) {
                    $lastReserve = $report['reserve_id']; ?>
                    <h3>Número da reserva: <?= $lastReserve ?></h3>
                    <h3>Total da reserva: <?= $report['total_value'] ?></h3>
                <?php } ?>

            <div class="data" style="border: black 1px solid; margin: 1px">

                <p><?= $report['product_name'] ?></p>
                <p>Preço Un.: R$
                    <?= brl_price_format($report['value'] / $report['quantity']) ?></p>
                <p>Quantidade: <?= $report['quantity'] ?></p>
                <p>Total (preço x quantidade): <?= $report['value'] ?></p>
                <p>Produto foi retirado? <?= $report['redeemed'] == 1 ? 'Sim' : 'Não' ?></p>
            </div>
        <?php } ?>
    <?php } ?>
</div>
