<?php
require_once 'common.php';

$connection = databaseConnection();

// $myCart takes the value of myCart from this session
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
    $myCart[intval($_POST['idProduct'])] = isset($myCart[intval($_POST['idProduct'])])
                                                ? $myCart[intval($_POST['idProduct'])] + 1
                                                : 1;
    $_SESSION['myCart'] = $myCart;
    header('Location: index.php');
    die();
}

$items = extractProducts($connection, $myCart, ALL_PRODUCTS);
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
        <tr class="elementOfTable" style="<?= (
                (isset($myCart[$items[$i]['id']]) && $items[$i]['inventory'] <= $myCart[$items[$i]['id']])
                || $items[$i]['inventory'] ==0
        ) ? 'display:none;' : ''?>">
            <td>
                <img class="phoneImage" src="<?= $items[$i]['image_path'] ?>">
            </td>
            <td>
                <?= $items[$i]['title'] ?><br>
                <?= $items[$i]['description'] ?><br>
                <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
                <?= $items[$i]['inventory'] - (isset($myCart[$items[$i]['id']]) ? $myCart[$items[$i]['id']] : 0) ?>
                <?= translate('left') ?><br>
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