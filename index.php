<?php
session_start();

require_once 'common.php';

$connection = databaseConnection();

// $my_cart takes the value of my_cart from this session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// I add the product the cart and I retain it within this session.
// In the same time I am checking if there is such id in database
if (
        isset($_POST['id_product'])
        && count(query(
                $connection,
                'SELECT id FROM products WHERE id = ?;',
                [intval($_POST['id_product'])]
        ))
) {
    // add the valid id of the product to cart
    $my_cart[intval($_POST['id_product'])] = '0';
    $_SESSION['my_cart'] = $my_cart;
}

$items = extract_products($connection, $my_cart, 'outside the cart');

?>
<?= doc_type_html() ?>
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
                    <button type="submit" class="link_button"> <?= translate('Add') ?> </button>
                </form>
            </td>

        </tr>
        <br>
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