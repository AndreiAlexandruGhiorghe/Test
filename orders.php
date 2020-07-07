<?php

require_once 'common.php';

$connection = databaseConnection();

$orderList = query(
    $connection,
    'SELECT * FROM order_details;',
    []
);

$itemsOfOrders = [];
foreach ($orderList as $order) {
    // order['id'] is the id from the order_detail table
    $orderItems = query(
        $connection,
        'SELECT p.id, p.title, p.description, p.price, p.image_path 
        FROM order_products o_p JOIN products p ON o_p.id_product = p.id
        WHERE o_p.id_order = ?;',
        [$order['id']]
    );
    // the index is the id of the order
    $itemsOfOrders[$order['id']] = $orderItems;
}

// if I have no orders I dont need to load the html part
if (!count($itemsOfOrders)) {
    die();
}

$sum = 0;
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
        <?php for ($i = 0; $i < count($itemsOfOrders[$order['id']]); $i++): ?>
            <tr class="elementOfTable">
                <td>
                    <img
                        class="phoneImage"
                        src="<?= 'http://localhost/Test/' . $itemsOfOrders[$order['id']][$i]['image_path'] ?>"
                    >
                </td>
                <td>
                    <?= $itemsOfOrders[$order['id']][$i]['title'] ?><br>
                    <?= $itemsOfOrders[$order['id']][$i]['description'] ?><br>
                    <?php
                    $sum += $itemsOfOrders[$order['id']][$i]['price'];
                    echo $itemsOfOrders[$order['id']][$i]['price'];
                    ?>
                    <?= translate('lei') ?><br>
                </td>
            </tr>
            <br>
        <?php endfor; ?>
        <tr>
            <td>
                <p>
                    <?= translate('Name: ') ?>
                    <?= translate($order['creation_date']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Name: ') ?>
                    <?= translate($order['name']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Address: ') ?>
                    <?= translate($order['address']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Comments: ') ?>
                    <?= translate($order['comments']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Total price: ') ?>
                    <?php
                    echo $sum;
                    $sum = 0;
                    ?>
                    <?= translate(' lei') ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <form action="order.php" method="POST">
                    <input type="hidden" name="idOrder" value="<?= $order['id'] ?>">
                    <input type="submit" name="submitButton" value="checkout">
                </form>
            </td>
        </tr>
        </tbody>
    </table>
<?php endforeach; ?>
</body>
</html>
