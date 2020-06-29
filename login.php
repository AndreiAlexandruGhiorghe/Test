<?php
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
        $connection = databaseConnection();

        $users = $connection->query("Select * from accounts where username = ? AND password = ?;",
            [$username_field, $password_field]);

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
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Login page</title>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
</head>
<body>
<form action="login.php" method="POST">
    <input type="text" class="input_type" name="username_field" placeholder="<?= translate('Username') ?>"
           value="<?= translate($username_field) ?>">
    <span class="error_field">* <?= translate($username_field_error) ?></span>
    <br>
    <input type="password" class="input_type" name="password_field" placeholder="<?= translate('Password') ?>"
           value="<?= translate($password_field) ?>">
    <span class="error_field">* <?= translate($password_field_error) ?></span>
    <br>
    <input type="submit" class="input_type_login" name="submit_button" value="<?= translate('Login') ?>">
    <span class="error_field"> <?= translate($login_failed_message) ?> </span>
</form>
</body>
</html>
