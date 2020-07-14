<?php
require_once 'common.php';

// in items are the list with all products
$items = extractProducts($connection, $myCart, INSIDE_CART);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> <?= translate('Cart Page') ?> </title>
    <style>
        .phoneImage {
            width: 100px;
            height: 100px;
        }

        #contentTable {
            border-style: groove;
        }

        .elementOfTable {
            border-style: ridge;
            display: block;
            border-collapse: collapse;
            margin: 0px 0px 0px 0px;
        }
    </style>
</head>
<body>

<table id="contentTable">
    <tbody>
    <?php for ($i = 0; $i < count($items); $i++): ?>
        <tr class="elementOfTable">
            <td>
                <img class="phoneImage" src="<?= 'http://localhost/Test/' . $items[$i]['image_path'] ?>">
            </td>
            <td>
                <?= $items[$i]['title'] ?><br>
                <?= $items[$i]['description'] ?><br>
                <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
                <?= $myCart[$items[$i]['id']]?> <?= translate('products') ?><br>
            </td>
        </tr>
        <br>
    <?php endfor; ?>
    <tr>
        <td>
            <p>
                <?= translate('Name: ') ?>
                <?= translate($inputData['nameField']) ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <?= translate('Address: ') ?>
                <?= translate($inputData['addressField']) ?>
            </p>
        </td>
    </tr>
    <tr>
        <td>
            <p>
                <?= translate('Comments: ') ?>
                <?= translate($inputData['commentsField']) ?>
            </p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
