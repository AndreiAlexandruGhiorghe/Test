<?php

require_once 'common.php';

checkAuthorization();

$connection = databaseConnection();

$orderList = query(
    $connection,
    'SELECT o_d.id, SUM(p.price * o_p.quantity) totalPrice
     FROM order_details o_d
     JOIN order_products o_p ON o_d.id = o_p.id_order
     JOIN products p ON o_p.id_product = p.id
     GROUP BY o_d.id'
);

// if I have no orders I dont need to load the html part
if (!count($orderList)) {
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="http://localhost/Test/frontend/style.css">
    <title><?= translate('Cart Page') ?></title>
</head>
<body>
    <?php foreach ($orderList as $order): ?>
        <table id="contentTable">
            <tbody>
                <tr>
                    <td>
                        <p>
                            <?= translate('Total price: ') ?>
                            <?= $order['totalPrice'] ?>
                            <?= translate(' lei') ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="order.php?idOrder=<?= $order['id'] ?>" class="linkButton">
                            <?= translate('See Order\'s details') ?>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
</body>
</html>
