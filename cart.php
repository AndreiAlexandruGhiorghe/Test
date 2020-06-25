<?php
session_start();

require_once 'common.php';

$connection = databaseConnection();

// in items are the list with all products
$items = query($connection, 'Select * from products;', []);

// I add the product the cart and I retain it within this session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// initialise fields errors
$name_field_error = '';
$address_field_error = '';

// initialise the input fields
$name_field = '';
$address_field = '';
$comments_field = '';

// I add the product the cart and the cookie retain it
if (isset($_POST['id_product'])) {
    unset($my_cart[intval($_POST['id_product'])]);
    $_SESSION['my_cart'] = $my_cart;
} elseif (isset($_POST['name_field'])) {
    // if I receive name_field that means a have other variables too
    $name_field = strip_tags($_POST['name_field']);

    if (strlen($name_field) < 5) {
        $name_field_error = 'The name should be at least 5 characters.';
    } elseif (strlen($name_field) > 18) {
        $name_field_error = 'The name should have maximum 18 characters';
    }

    $address_field = strip_tags($_POST['address_field']);
    if (!filter_var($address_field, FILTER_VALIDATE_EMAIL)) {
        $address_field_error = 'Invalid email address';
    }

    $comments_field = strip_tags($_POST['comments_field']);
    if ($name_field_error == '' && $address_field_error == '') {
        mail($address_field,translate('Your command'),'Test');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title>Main Page</title>
</head>
<body>

<table id="content_table">
    <tbody>
    <?php for ($i = 0; $i < count($items); $i++): ?>
        <?php ?>
        <?php if (isset($my_cart[intval($items[$i]['id'])])): ?>
            <tr class="element_of_table">
                <td>
                    <img class="phone_image" src="<?= '.' . $items[$i]['image_path'] ?>">
                </td>
                <td>
                    <?= $items[$i]['title'] ?><br>
                    <?= $items[$i]['description'] ?><br>
                    <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
                </td>
                <td>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="id_product" value="<?= $items[$i]['id'] ?>">
                        <a onclick="this.parentNode.submit();"> <?= translate('Remove') ?> </a>
                    </form>
                </td>
            </tr>
            <br>
        <?php endif; ?>
    <?php endfor; ?>
    <form action="cart.php" method="POST">
        <tr>
            <td>
                <input class="input_type" type="text" name="name_field" placeholder="<?= translate('Name') ?>" value="<?= translate($name_field) ?>">
                <span class="error_field">* <?= translate($name_field_error) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input class="input_type" type="text" name="address_field" placeholder="<?= translate('Contact deatails')?>" value="<?= translate($address_field) ?>">
                <span class="error_field">* <?= translate($address_field_error) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <textarea id="comments_section" class="input_type" type="text" name="comments_field" placeholder="<?= translate('Comments') ?>"><?= translate($comments_field) ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <a href="index.php"><?= translate('Go to index') ?></a>
                <input type="submit" name="submit_button" value="Checkout">
            </td>
        </tr>
    </form>
    </tbody>
</table>
</body>
</html>
