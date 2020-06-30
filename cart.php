<?php

require_once 'common.php';

$connection = databaseConnection();

// I take the data that is inside my_cart from session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// initialise fields errors
$name_field_error = '';
$address_field_error = '';

// initialise the input fields
$input_data = [];
$input_data['name_field'] = $input_data['address_field'] = $input_data['comments_field'] = '';

// I add the product the cart and the cookie retain it
if (isset($_POST['id_product'])) {
    unset($my_cart[intval($_POST['id_product'])]);
    $_SESSION['my_cart'] = $my_cart;
} elseif (isset($_POST['name_field']) && $_POST['address_field']) {
    $input_data['name_field'] = $_POST['name_field'];//strip_tags($_POST['name_field']);
    if (strlen($input_data['name_field']) < 5 || strlen($input_data['name_field']) > 18) {
        $name_field_error = 'The name should be between 5 and 18 characters.';
    }

    $input_data['address_field'] = strip_tags($_POST['address_field']);
    if (!filter_var($input_data['address_field'], FILTER_VALIDATE_EMAIL)) {
        $address_field_error = 'Invalid email address';
    }

    $input_data['comments_field'] = strip_tags($_POST['comments_field']);
    if ($name_field_error == '' && $address_field_error == '') {
        $_SESSION['input_data'] = $input_data;
        header('Location: cart_mail.php');
        die();
    }
}

// in items are the list with all products
$items = extract_products($connection, $my_cart, INSIDE_CART);

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="http://localhost/Test/frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Cart Page') ?> </title>
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
                <form method="post" action="cart.php">
                    <input type="hidden" name="id_product" value="<?= $items[$i]['id'] ?>">
                    <button type="submit" class="link_button"> <?= translate('Remove') ?> </button>
                </form>
            </td>
        </tr>
        <br>
    <?php endfor; ?>
    <form action="cart.php" method="POST">
        <tr>
            <td>
                <input class="input_type" type="text" name="name_field"
                       placeholder="<?= translate('Name') ?>"
                       value="<?= $input_data['name_field'] ?>">
                <span class="error_field">* <?= translate($name_field_error) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input class="input_type" type="text" name="address_field"
                       placeholder="<?= translate('Contact deatails') ?>"
                       value="<?= $input_data['address_field'] ?>">
                <span class="error_field">* <?= translate($address_field_error) ?></span>
            </td>
        </tr>
        <tr>
            <td>
            <textarea id="comments_section" class="input_type" type="text" name="comments_field"
                      placeholder="<?= translate('Comments') ?>">
                <?= $input_data['comments_field'] ?>
            </textarea>
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
