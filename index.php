<?php
require_once 'config.php';
require_once 'common.php';

$connection = new DatabaseConnection($server_name, $username, $password, $db_name);
$items = $connection->query("Select * from $db_name.products;",[]);


require_once 'frontend/main_page.php';
