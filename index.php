<?php
require_once 'config.php';
require_once 'common.php';

$connection = new DatabaseConnection($server_name, $username, $password, $db_name);

//in items are the list with all products
$items = $connection->query("Select * from products;", []);

//when my cookie is set I convert the data to array and assign it to $my_cart
// or else I just initialise it as an empty array
$my_cart = isset($_COOKIE['my_cart']) ? json_decode($_COOKIE['my_cart'], true) : [];

//I add the product the cart and the cookie retain it
if (isset($_GET['id_product'])) {
    $my_cart += array(intval($_GET['id_product']) => '0');
    setcookie('my_cart', json_encode($my_cart), time() +3600);
}



//accessing the frontend part of index
require_once 'frontend/main_page.php';
