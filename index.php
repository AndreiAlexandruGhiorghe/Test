<?php
require_once 'config.php';
require_once 'common.php';

$connection = new DatabaseConnection($server_name, $username, $password, $db_name);
$items = $connection->query("Select * from products;", []);


if (isset($_COOKIE['my_cart'])) {
    $my_cart = json_decode($_COOKIE['my_cart'], true);
} else {
    $my_cart = [];
}

if (isset($_GET['id_product'])) {
    $my_cart += array(intval($_GET['id_product']) => '0');
    setcookie('my_cart', json_encode($my_cart), time() +3600);
}

require_once 'frontend/main_page.php';
