<?php
require_once 'config.php';
require_once 'common.php';

$connection = new DatabaseConnection($server_name, $username, $password, $db_name);

//in items are the list with all products
$items = $connection->query("Select * from products;", []);

//when my cookie is set I convert the data to array and assign it to $my_cart
// or else I just initialise it as an empty array
$my_cart = isset($_COOKIE['my_cart']) ? json_decode($_COOKIE['my_cart'], true) : [];

//initialise fields errors
$name_field_error = '';
$address_field_error = '';

//initialise the input fields
$name_field = '';
$address_field = '';
$comments_field = '';

//I add the product the cart and the cookie retain it
if (isset($_GET['id_product'])) {
    unset($my_cart[intval($_GET['id_product'])]);
    setcookie('my_cart', json_encode($my_cart), time() + 3600);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

//accessing the frontend part of cart
require_once 'frontend/cart_page.php';