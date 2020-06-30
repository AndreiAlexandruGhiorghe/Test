<?php
require_once 'common.php';

$connection = databaseConnection();

// $my_cart takes the value of my_cart from this session
$myCart = isset($_SESSION['myCart']) ? $_SESSION['myCart'] : [];

// I add the product the cart and I retain it within this session.
// In the same time I am checking if there is such id in database
if (
    isset($_POST['idProduct'])
    && count(query(
        $connection,
        'SELECT id FROM products WHERE id = ?;',
        [intval($_POST['idProduct'])]
    ))
) {
    // add the valid id of the product to cart
    // the key is the id of the product and the value is the quantity
    $myCart[intval($_POST['idProduct'])] = 1;
    $_SESSION['myCart'] = $myCart;
}

$items = extractProducts($connection, $myCart, OUTSIDE_CART);
?>
<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Main Page') ?> </title>
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
            <td>
                <form method="post" action="index.php">
                    <input type="hidden" name="idProduct" value="<?= $items[$i]['id'] ?>">
                    <button type="submit" class="linkButton"> <?= translate('Add') ?> </button>
                </form>
            </td>
        </tr>
    <?php endfor; ?>
    <tr>
        <td>
            <a href="cart.php"> <?= translate('Go to cart') ?> </a>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>