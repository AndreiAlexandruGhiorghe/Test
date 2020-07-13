<?php

require_once 'common.php';

checkAuthorization();

$connection = databaseConnection();

$order = query(
    $connection,
    'SELECT
            p.id,
            p.title,
            p.description,
            p.price,
            p.image_path,
            o_d.name,
            o_d.address,
            o_d.comments,
            o_d.creation_date
     FROM order_products o_p
     JOIN products p ON o_p.id_product = p.id
     JOIN order_details o_d ON o_p.id_order = o_d.id
     WHERE o_d.id = ?',
    [isset($_GET['idOrder']) ? $_GET['idOrder'] : 0]
);
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
        <?php for ($i = 0; $i < count($order); $i++): ?>
            <tr class="elementOfTable">
                <td>
                    <img
                            class="phoneImage"
                            src="<?= 'http://localhost/Test/' . $order[$i]['image_path'] ?>"
                    >
                </td>
                <td>
                    <?= $order[$i]['title'] ?><br>
                    <?= $order[$i]['description'] ?><br>
                    <?= $order[$i]['price'] ?><?= translate('lei') ?><br>
                </td>
            </tr>
            <br>
        <?php endfor; ?>
        <tr>
            <td>
                <p>
                    <?= translate('Creation date: ') ?>
                    <?= isset($order[0]['creation_date']) ? $order[0]['creation_date'] : '' ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Name: ') ?>
                    <?= isset($order[0]['name']) ? $order[0]['name'] : '' ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Address: ') ?>
                    <?= isset($order[0]['address']) ? $order[0]['address'] : '' ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Comments: ') ?>
                    <?= isset($order[0]['comments']) ? $order[0]['comments'] : '' ?>
                </p>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
