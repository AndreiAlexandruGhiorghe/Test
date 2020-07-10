<?php

require_once 'common.php';

checkAuthorization();

$connection = databaseConnection();

$orderList = query(
    $connection,
    'SELECT id FROM order_details;'
);

$items = query(
    $connection,
    'SELECT p.id, p.price, o_P.id_order
     FROM order_products o_p 
     JOIN products p ON o_p.id_product = p.id'
);

$itemsOfOrders = [];
foreach ($orderList as $order) {
    // order['id'] is the id from the order_detail table
    $sum = 0;
    foreach ($items as $item) {
        $sum = ($item['id_order'] == $order['id']) ? $sum + $item['price'] : $sum;
    }
    // the index is the id of the order
    $itemsOfOrders[$order['id']]['totalPrice'] = $sum;
}

// if I have no orders I dont need to load the html part
if (!count($itemsOfOrders)) {
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
                            <?= $itemsOfOrders[$order['id']]['totalPrice'] ?>
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
