<?php

require_once 'common.php';

$connection = databaseConnection();

// I add the product the cart and I retain it within this session
$my_cart = isset($_SESSION['my_cart']) ? $_SESSION['my_cart'] : [];

// initialise fields errors
$name_field_error = '';
$address_field_error = '';

// initialise the input fields
$name_field = '';
$address_field = '';
$comments_field = '';

// send_email is true when the user fully completed and send the form
$send_email = false;

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
        $send_email = true;
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
            <?php ?>
            <?php if (isset($my_cart[intval($items[$i]['id'])])): ?>
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
            <?php endif; ?>
        <?php endfor; ?>
        <form action="cart.php" method="POST">
            <tr>
                <td>
                    <input class="input_type" type="text" name="name_field" placeholder="<?= translate('Name') ?>"
                           value="<?= translate($name_field) ?>">
                    <span class="error_field">* <?= translate($name_field_error) ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="input_type" type="text" name="address_field"
                           placeholder="<?= translate('Contact deatails') ?>" value="<?= translate($address_field) ?>">
                    <span class="error_field">* <?= translate($address_field_error) ?></span>
                </td>
            </tr>
            <tr>
                <td>
                <textarea id="comments_section" class="input_type" type="text" name="comments_field"
                          placeholder="<?= translate('Comments') ?>"><?= translate($comments_field) ?></textarea>
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
<?php
if ($send_email) {
    $html_page = ob_get_clean();
    $pos = strpos($html_page, '<head>');

    $first_part_of_html = substr($html_page, 0, $pos + 6);
    $first_part_of_html = (($first_part_of_html !== false) ? $first_part_of_html : '');

    $second_part_of_html = substr($html_page, $pos + 6);
    $second_part_of_html = (($second_part_of_html !== false) ? $second_part_of_html : '');

    $css_file_size = filesize('frontend/style.css');
    $css_file_size = (($css_file_size !== false) ? $css_file_size : 0);

    $css_file = fopen('frontend/style.css', 'r');
    $css_file = (($css_file !== false) ? $css_file : 0);

    $css_file_content = ($css_file_size != 0 && $css_file != 0) ? fread($css_file, $css_file_size) : '';
    $css_file_content = ($css_file_content === false) ? '' : $css_file_content;

    $html_page = $first_part_of_html .
        '<style>' .
        $css_file_content .
        '</style>' .
        $second_part_of_html;
    // I use "" because otherwise when I send them on email
    // \r\n are saw as plain text
    $headers = 'From: ' . SENDER_ADDRESS . "\r\n";
    $headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

    mail($address_field, 'Your Cart', $html_page, $headers);

    echo $html_page;
}
?>