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
            <tbody>
            <?php
            $lastReserveId = null;
            $rowCount = 0;
            foreach ($reports as $key => $report) {
                $isNewReserve = ($lastReserveId !== $report['reserve_id']);
                $nextReserveId = $reports[$key + 1]['reserve_id'] ?? null;

                if ($isNewReserve) {
                    // Conta quantas linhas a reserva ocupa
                    $rowCount = count(array_filter($reports, fn($r) => $r['reserve_id'] === $report['reserve_id']));
                }
                ?>
                <tr>
                    <?php if ($isNewReserve) { ?>
                        <td rowspan="<?= $rowCount ?>"><?= date_fmt($report['reserved_at'], 'd/m/Y H:i:s') ?></td>
                        <td rowspan="<?= $rowCount ?>"><?= $report['reserve_id'] ?></td>
                        <td rowspan="<?= $rowCount ?>">R$ <?= brl_price_format($report['total_value']) ?></td>
                    <?php } ?>
                    <td><?= $report['product_name'] ?></td>
                    <td>R$ <?= brl_price_format($report['value'] / $report['quantity']) ?></td>
                    <td><?= $report['quantity'] ?></td>
                    <td>R$ <?= brl_price_format($report['value']) ?></td>
                    <td><?= $report['user_id'] ?></td>
                    <td><?= $report['redeemed'] ? 'Sim' : 'Não' ?></td>
                </tr>
                <?php
                $lastReserveId = $report['reserve_id'];
            }
            ?>
            </tbody>
        </table>
    <?php } ?>
</div>