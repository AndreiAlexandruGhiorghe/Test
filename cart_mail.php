<?php

require_once 'common.php';

$connection = databaseConnection();

// I arrived there through post method from cart.php
$inputData =  (isset($_SESSION['inputData'])) ? $_SESSION['inputData'] : [
        'nameField' => '',
        'addressField' => '',
        'commentsField' => '',
];

// I add the product the cart and I retain it within this session
$myCart = isset($_SESSION['myCart']) ? $_SESSION['myCart'] : [];

// in items are the list with all products
$items = extractProducts($connection, $myCart, INSIDE_CART);

ob_start();
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
                    <img class="phoneImage" src="<?= $items[$i]['image_path'] ?>">
                </td>
                <td>
                    <?= $items[$i]['title'] ?><br>
                    <?= $items[$i]['description'] ?><br>
                    <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
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
<?php
$html_page = ob_get_clean();

// I use "" because otherwise when I send them on email
// \r\n are saw as plain text
$headers = 'From: ' . SENDER_ADDRESS . "\r\n";
$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

mail($inputData['addressField'], 'Your Cart', $html_page, $headers);

// empty the cart
$_SESSION['myCart'] = [];

header('Location: index.php');
die();
?>