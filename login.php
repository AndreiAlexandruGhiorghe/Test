<?php
require_once 'common.php';

$usernameField = '';
$usernameFieldError = '';
$passwordField = '';
$passwordFieldError = '';
$loginFailedMessage = '';


if (isset($_POST['submitButton'])) {

    if ($_POST['usernameField'] != '') {
        $usernameField = strip_tags($_POST['usernameField']);
    } else {
        $usernameFieldError = translate('Enter a username');
    }
    if ($_POST['passwordField'] != '') {
        $passwordField = strip_tags($_POST['passwordField']);
    } else {
        $passwordFieldError = translate('Enter a password');
    }
    if ($usernameField != '' && $passwordField != '') {
        $connection = databaseConnection();

        $users = query(
                $connection,
                'Select * from accounts where username = ? AND password = ?;',
                [$usernameField, $passwordField]
        );
        if (
            isset($users[0]['username'])
            && $users[0]['username'] == $usernameField
            && $users[0]['password'] == $passwordField
        ) {
            if ($users[0]['admin'] == true) {
                $_SESSION['admin'] = true;
                header('Location: products.php');
            } else {
                $_SESSION['admin'] = false;
                header('Location: index.php');
            }
            die();

        } else {
            $loginFailedMessage = 'Wrong username or password';
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
    <input type="text" class="inputType" name="usernameField" placeholder="<?= translate('Username') ?>"
           value="<?= translate($usernameField) ?>">
    <span class="errorField">* <?= translate($usernameFieldError) ?></span>
    <br>
    <input type="password" class="inputType" name="passwordField" placeholder="<?= translate('Password') ?>"
           value="<?= translate($passwordField) ?>">
    <span class="errorField">* <?= translate($passwordFieldError) ?></span>
    <br>
    <input type="submit" class="inputTypeLogin" name="submitButton" value="<?= translate('Login') ?>">
    <span class="errorField"> <?= translate($loginFailedMessage) ?> </span>
</form>
</body>
</html>
