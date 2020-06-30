<?php

require_once 'common.php';

$connection = databaseConnection();

// I arrived there through post method from cart.php
$input_data =  (isset($_SESSION['input_data'])) ? $_SESSION['input_data'] : [
        'name_field' => '',
        'address_field' => '',
        'comments_field' => '',
];
//
//var_dump($_SESSION['input_data']);

// I add the product the cart and I retain it within this session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// in items are the list with all products
$items = extract_products($connection, $my_cart, INSIDE_CART);

ob_start();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title> <?= translate('Cart Page') ?> </title>
        <style>
            .phone_image {
                width: 100px;
                height: 100px;
            }

            #content_table {
                border-style: groove;
            }

            .element_of_table {
                border-style: ridge;
                display: block;
                border-collapse: collapse;
                margin: 0px 0px 0px 0px;
            }
        </style>
    </head>
    <body>

    <table id="content_table">
        <tbody>
        <?php for ($i = 0; $i < count($items); $i++): ?>
            <tr class="element_of_table">
                <td>
                    <img class="phone_image" src="<?= $items[$i]['image_path'] ?>">
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
                    <?= translate($input_data['name_field']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Address: ') ?>
                    <?= translate($input_data['address_field']) ?>
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <?= translate('Comments: ') ?>
                    <?= translate($input_data['comments_field']) ?>
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

mail($input_data['address_field'], 'Your Cart', $html_page, $headers);

// empty the cart
$_SESSION['my_cart'] = [];

header('Location: index.php');
die();
?>