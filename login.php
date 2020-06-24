<?php
require_once 'config.php';
require_once 'common.php';

if (isset($_POST['submit_button'])) {
    if (isset($_POST['username_field'])) {

    }
    $connection = new DatabaseConnection($server_name, $username, $password, $db_name);

    $users = $connection->query("Select * from accounts where username = ? AND password = ?;", []);

}


require_once 'frontend/login_page.php';