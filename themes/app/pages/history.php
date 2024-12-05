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

<div class="sales-report">
    <h1>Minhas reservas!</h1>

    <?php if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto comprado!</p>

    <?php } else { ?>

        <?php foreach ($reports as $report) { ?>

            <?php if ($lastDate != date_fmt($report['reserved_at'], 'd/m/Y')) {
                $lastDate = date_fmt($report['reserved_at'], 'd/m/Y'); ?>
                <div class="date-separator">
                    <h2>Reservas do dia <?= $lastDate ?></h2>
                </div>
            <?php } ?>

            <?php if ($lastReserve != $report['reserve_id']) {
                $lastReserve = $report['reserve_id']; ?>
                <div class="history-items-info">
                    <h3 id="num-reserva-text">Número da reserva: <?= $lastReserve ?></h3>
                    <h3 id="total-reserva-text">Total da reserva: <?= $report['total_value'] ?></h3>
                </div>
            <?php } ?>

            <div class="history-item-grid">
                <div class="history-grid-item">
                    <p class="history-item-name"><?= $report['product_name'] ?></p>
                    <p class="history-item-price">Preço Un.: R$ <?= brl_price_format($report['value'] / $report['quantity']) ?></p>
                    <p class="history-item-amount">Quantidade: <?= $report['quantity'] ?></p>
                    <p class="history-item-total-price">Total (preço x quantidade): <?= $report['value'] ?></p>
                    <p class="history-was-item-removed">Produto foi retirado? <?= $report['redeemed'] == 1 ? 'Sim' : 'Não' ?></p>
                </div>
            </div>
        <?php } ?>

    <?php } ?>
</div>