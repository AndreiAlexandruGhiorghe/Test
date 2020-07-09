<?php

require_once 'common.php';

$connection = databaseConnection();

$order = query(
    $connection,
    'SELECT * FROM order_details WHERE id = ?;',
    [$_POST['idOrder']]
);
if (count($order)) {
    $order = $order[0];

    $orderItems = query(
        $connection,
        'SELECT p.id, p.title, p.description, p.price, p.image_path 
    FROM order_products o_p JOIN products p ON o_p.id_product = p.id
    WHERE o_p.id_order = ?;',
        [$order['id']]
    );
} else {
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
<table id="contentTable">
    <tbody>
        <?php for ($i = 0; $i < count($orderItems); $i++): ?>
            <tr class="elementOfTable">
                <td>
                    <img
                            class="phoneImage"
                            src="<?= 'http://localhost/Test/' . $orderItems[$i]['image_path'] ?>"
                    >
                </td>
                <td>
                    <?= $orderItems[$i]['title'] ?><br>
                    <?= $orderItems[$i]['description'] ?><br>
                    <?= $orderItems[$i]['price'] ?><?= translate('lei') ?><br>
                </td>
            </tr>
            <br>
        <?php endfor; ?>
        <tr>
            <td>
                <p>
                    <?= translate('Name: ') ?>
                    <?= $order['creation_date'] ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Name: ') ?>
                    <?= $order['name'] ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Address: ') ?>
                    <?= $order['address'] ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Comments: ') ?>
                    <?= $order['comments'] ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
