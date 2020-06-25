<?php
session_start();

require_once 'common.php';

$connection = databaseConnection();

// in items are the list with all products
$items = query($connection, 'Select * from products;', []);

// $my_cart takes the value of my_cart from this session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// I add the product the cart and I retain it within this session
if (isset($_POST['id_product'])) {
    $my_cart += [intval($_POST['id_product']) => '0'];
    $_SESSION['my_cart'] = $my_cart;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Main Page') ?> </title>
</head>
<body>

<table id="content_table">
    <tbody>
    <?php for ($i = 0; $i < count($items); $i++): ?>
        <?php if (!isset($my_cart[intval($items[$i]['id'])])): ?>
            <tr class="element_of_table">
                <td>
                    <img class="phone_image" src="<?= $items[$i]['image_path'] ?>">
                </td>
                <td>
                    <?= $items[$i]['title'] ?><br>
                    <?= $items[$i]['description'] ?><br>
                    <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
                </td>
                <td>
                    <form method="post" action="index.php">
                        <input type="hidden" name="id_product" value="<?= $items[$i]['id'] ?>">
                        <a onclick="this.parentNode.submit();"> <?= translate('Add') ?> </a>
                    </form>
                </td>

            </tr>
            <br>
        <?php endif; ?>
    <?php endfor; ?>
    <tr>
        <td>
            <form method="post" action="cart.php">
                <a onclick="this.parentNode.submit();"> <?= translate('Go to cart') ?> </a>
            </form>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>