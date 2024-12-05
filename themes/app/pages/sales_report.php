<?php

use Autoload\Core\DB\Select;

if (!$session->has('authSeller')) {
    redirect('../app');
}

$search = new Select();
$reports = $search->executeQuery('
SELECT r.reserve_id, p.name as product_name, pr.quantity, pr.total_value as value, r.user_id, r.reserved_at, r.total_value, r.redeemed
FROM product_reserve pr
LEFT JOIN product p ON p.product_id = pr.product_id
LEFT JOIN reserve r ON pr.reserve_id = r.reserve_id
WHERE p.seller_id = :seller_id
ORDER BY reserved_at DESC', "seller_id={$session->authSeller}");

$lastDate = null;
$lastReserve = null;
?>

<div class="sales-report">
    <h1>Relatórios</h1>

    <?php if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto vendido!</p>

    <?php } else { ?>
        <table class="sales-report-table">
            <thead>
            <tr>
                <th>Data e Hora</th>
                <th>ID da reserva</th>
                <th>Total da reserva</th>
                <th>Produto</th>
                <th>Preço Unitário</th>
                <th>Quantidade</th>
                <th>Total (Preço x Qnt.)</th>
                <th>Reservado para</th>
                <th>Foi retirado?</th>
            </tr>
            </thead>

            <?php foreach ($reports as $report) { ?>
                <tr>
                    <td><?= date_fmt($report['reserved_at'], 'd/m/Y H:i:s') ?></td>
                    <td><?= $report['reserve_id'] ?></td>
                    <td><?= $report['total_value'] ?></td>
                    <td><?= $report['product_name'] ?></td>
                    <td>R$ <?= brl_price_format($report['value'] / $report['quantity']) ?></td>
                    <td><?= $report['quantity'] ?></td>
                    <td>R$ <?= brl_price_format($report['value']) ?></td>
                    <td><?= $report['user_id'] ?></td>
                    <td><?= $report['redeemed'] ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>
</div>
