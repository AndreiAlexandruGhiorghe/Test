<?php
require_once 'config.php';
require_once 'common.php';

$username_field = '';
$username_field_error = '';
$password_field = '';
$password_field_error = '';
$login_failed_message = '';


if (isset($_POST['submit_button'])) {

    if ($_POST['username_field'] != '') {
        $username_field = strip_tags($_POST['username_field']);
    } else {
        $username_field_error = translate('Enter a username');
    }
    if ($_POST['password_field'] != '') {
        $password_field = strip_tags($_POST['password_field']);
    } else {
        $password_field_error = translate('Enter a password');
    }
    if ($username_field != '' && $password_field != '') {
        $connection = new DatabaseConnection(
            constant('server_name'),
            constant('username'),
            constant('password'),
            constant('db_name')
        );

        $users = $connection->query("Select * from accounts where username = ? AND password = ?;", [$username_field, $password_field]);

        if (
            isset($users[0]['username'])
            && $users[0]['username'] == $username_field
            && $users[0]['password'] == $password_field
        ) {
            header('Location: index.php');
            die();
        } else {
            $login_failed_message = 'Wrong username or password';
        }
    }

}
//echo $username_field;
//echo $username_field_error;
//echo $password_field;
//echo $password_field_error;
//echo $login_failed_message;

require_once 'frontend/login_page.php';