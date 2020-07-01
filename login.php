<?php
require_once 'common.php';

$usernameField = '';
$passwordField = '';
$inputErrors = [];

if (isset($_SESSION['username']) && $_SESSION['username'] == ADMIN_CREDENTIALS['username']) {
    header('Location: products.php');
    die();
}

if (isset($_POST['submitButton'])) {

    if ($_POST['usernameField']) {
        $usernameField = strip_tags($_POST['usernameField']);
    } else {
        $inputErrors['usernameFieldError'] = translate('* Enter a username');
    }

    if ($_POST['passwordField']) {
        $passwordField = strip_tags($_POST['passwordField']);
    } else {
        $inputErrors['passwordFieldError'] = translate('* Enter a password');
    }

    if (!count($inputErrors)) {
        if (
                $usernameField == ADMIN_CREDENTIALS['username']
                && $passwordField == ADMIN_CREDENTIALS['password']
        ) {
            $_SESSION['username'] = $usernameField;
            header('Location: products.php');
            die();
        }
        $inputErrors['loginFailedMessage'] = 'Wrong username or password';
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
    <span class="errorField"> <?= translate(
            isset($inputErrors['usernameFieldError']) ? $inputErrors['usernameFieldError'] : ''
        ) ?></span>
    <br>
    <input type="password" class="inputType" name="passwordField" placeholder="<?= translate('Password') ?>"
           value="<?= translate($passwordField) ?>">
    <span class="errorField"> <?= translate(
            isset($inputErrors['passwordFieldError']) ? $inputErrors['passwordFieldError'] : ''
        ) ?> </span>
    <br>
    <input type="submit" class="inputTypeLogin" name="submitButton" value="<?= translate('Login') ?>">
    <span class="errorField"> <?= translate(
            isset($inputErrors['loginFailedMessage']) ? $inputErrors['loginFailedMessage'] : ''
        ) ?> </span>
</form>
</body>
</html>
