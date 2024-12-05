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

<div class="sales_report">
    <h1>Relatórios</h1>

    <?php if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto vendido!</p>

    <?php } else { ?>

        <table>
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

        <!--        --><?php //foreach ($reports as $report) { ?>
        <!--            <div class="data">-->
        <!--                --><?php
//                if ($lastDate != date_fmt($report['reserved_at'], 'd/m/Y')) {
//                    $lastDate = date_fmt($report['reserved_at'], 'd/m/Y'); ?>
        <!---->
        <!--                    <div class='date'>-->
        <!--                        <h2>--><?php //= $lastDate ?><!--</h2>-->
        <!--                    </div>-->
        <!--                --><?php //} ?>
        <!---->
        <!--                --><?php //if ($lastReserve != $report['reserve_id']) {
//                    $lastReserve = $report['reserve_id']; ?>
        <!--                    <h3>ID da reserva: --><?php //= $lastReserve ?><!--</h3>-->
        <!--                    <h3>Total da reserva: --><?php //= $report['total_value'] ?><!--</h3>-->
        <!--                --><?php //} ?>
        <!---->
        <!--                <div class="sales_data" style="border: black 1px solid; margin: 1px">-->
        <!--                    <p>--><?php //= $report['product_name'] ?><!--</p>-->
        <!--                    <p>Preço Un.: R$-->
        <!--                        --><?php //= brl_price_format($report['value'] / $report['quantity']) ?><!--</p>-->
        <!--                    <p>Quantidade: --><?php //= $report['quantity'] ?><!--</p>-->
        <!--                    <p>Total (preço x quantidade): --><?php //= $report['value'] ?><!--</p>-->
        <!--                    <p>Reservado para:-->
        <!--                        --><?php //= $search->selectFirst('user', 'WHERE user_id = :id', "id={$report['user_id']}", 'name')['name'] ?>
        <!--                    </p>-->
        <!--                    <p>Produto foi retirado? --><?php //= $report['redeemed'] == 1 ? 'Sim' : 'Não' ?><!--</p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        --><?php //} ?>
    <?php } ?>
</div>