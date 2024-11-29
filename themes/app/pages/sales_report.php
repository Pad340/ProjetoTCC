<?php

use Autoload\Core\DB\Select;

if (!$session->has('authSeller')) {
    redirect('../app');
}

$search = new Select();
$reports = $search->executeQuery('
SELECT p.name as product_name, pr.quantity, pr.total_value as value, r.user_id, r.reserved_at, r.total_value, r.redeemed
FROM product_reserve pr
LEFT JOIN product p ON p.product_id = pr.product_id
LEFT JOIN reserve r ON pr.reserve_id = r.reserve_id
WHERE p.seller_id = :seller_id
ORDER BY reserved_at DESC', "seller_id={$session->authSeller}");

$last_date = null;
?>

<div class="sales_report">
    <?php
    if (empty($reports)) { ?>
        <p class="phrase">Nenhum produto vendido!</p>
    <?php } else { ?>

        <?php foreach ($reports as $key => $report) { ?>
            <div class="data">
                <?php
                if ($last_date != date_fmt($report['reserved_at'], 'd/m/Y')) {
                    $last_date = date_fmt($report['reserved_at'], 'd/m/Y'); ?>

                    <div class='date'><?= $last_date ?></div>

                <?php } ?>

                <div class="sales_data" style="border: black 1px solid; margin: 1px">
                    <p><b>Produto:</b> <?= $report['product_name'] ?></p>
                    <p><b>Preço Un.: R$</b>
                        <?= brl_price_format($report['value']) ?></p>
                    <p><b>Quantidade:</b> <?= $report['quantity'] ?></p>
                    <p><b>Total:</b> <?= brl_price_format($report['total_value']) ?></p>
                    <p><b>Produto foi retirado? </b><?= $report['redeemed'] ?></p>
                    <p><b>Reservado para:</b>
                        <?= $search->selectFirst('user', 'WHERE user_id = :id', "id={$report['user_id']}", 'name')['name'] ?>
                    </p>
                </div>

            </div>
        <?php } ?>
    <?php } ?>
</div>
