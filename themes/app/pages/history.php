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

<div class="history">
    <h1>Meu histórico de compras</h1>

    <?php if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto comprado!</p>
    <?php } else { ?>
        <?php foreach ($reports as $report): ?>
            <?php if ($lastDate != date_fmt($report['reserved_at'], 'd/m/Y')): ?>
                <?php $lastDate = date_fmt($report['reserved_at'], 'd/m/Y'); ?>
                <div class="date-separator">
                    <h2>Reservas do dia <?= $lastDate ?></h2>
                </div>
            <?php endif; ?>

            <?php if ($lastReserve != $report['reserve_id']): ?>
                <?php $lastReserve = $report['reserve_id']; ?>
                <div class="reserve-group">
                <div class="history-items-info">
                    <h3>Número da reserva: <?= $lastReserve ?></h3>
                    <h3>Total da reserva: R$ <?= number_format($report['total_value'], 2, ',', '.') ?></h3>
                </div>
                <div class="history-item-grid">
            <?php endif; ?>

            <div class="history-grid-item">
                <p class="history-item-name"><?= $report['product_name'] ?></p>
                <p class="history-item-price">Preço Un.: R$ <?= number_format($report['value'] / $report['quantity'], 2, ',', '.') ?></p>
                <p class="history-item-amount">Quantidade: <?= $report['quantity'] ?></p>
                <p class="history-item-total-price">Total: R$ <?= number_format($report['value'], 2, ',', '.') ?></p>
                <p class="history-was-item-removed">Produto foi retirado? <?= $report['redeemed'] == 1 ? 'Sim' : 'Não' ?></p>
            </div>

            <?php if (end($reports) === $report || $lastReserve != $reports[array_search($report, $reports) + 1]['reserve_id']): ?>
                </div> <!-- Fechando o grid -->
                </div> <!-- Fechando o grupo de reserva -->
            <?php endif; ?>

        <?php endforeach; ?>
    <?php } ?>
</div>