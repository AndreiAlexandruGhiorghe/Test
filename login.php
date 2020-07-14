<?php
require_once 'common.php';

// checking if the user is allready logged in
if (isset($_SESSION['username']) && $_SESSION['username'] == ADMIN_CREDENTIALS['USERNAME']) {
    header('Location: products.php');
    die();
}

// username and password
$inputData = [];
// the errors from username, password and login overall
$inputErrors = [];

if (isset($_POST['submitButton'])) {
    if ($_POST['usernameField']) {
        $inputData['usernameField'] = strip_tags($_POST['usernameField']);
    } else {
        $inputErrors['usernameFieldError'] = 'Enter a username';
    }
    if ($_POST['passwordField']) {
        $inputData['passwordField'] = strip_tags($_POST['passwordField']);
    } else {
        $inputErrors['passwordFieldError'] = 'Enter a password';
    }
    if (!count($inputErrors)) {
        if (
                $inputData['usernameField'] == ADMIN_CREDENTIALS['USERNAME']
                && $inputData['passwordField'] == ADMIN_CREDENTIALS['PASSWORD']
        ) {
            $_SESSION['username'] = $inputData['usernameField'];
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
        <input
                type="text"
                class="inputType"
                name="usernameField"
                placeholder="<?= translate('Username') ?>"
                value="<?= isset($inputData['usernameField']) ? $inputData['usernameField'] : '' ?>"
        >
        <span class="errorField">
            <?= isset($inputErrors['usernameFieldError']) ? '* ' . translate($inputErrors['usernameFieldError']) : '' ?>
        </span>
        <br>
        <input
                type="password"
                class="inputType"
                name="passwordField"
                placeholder="<?= translate('Password') ?>"
                value="<?= isset($inputData['passwordField']) ? $inputData['passwordField'] : '' ?>"
        >
        <span class="errorField">
            <?= isset($inputErrors['passwordFieldError']) ? '* ' . translate($inputErrors['passwordFieldError']) : '' ?>
        </span>
        <br>
        <input type="submit" class="inputTypeLogin" name="submitButton" value="<?= translate('Login') ?>">
        <span class="errorField">
            <?= isset($inputErrors['loginFailedMessage']) ? '* ' . translate($inputErrors['loginFailedMessage']) : '' ?>
        </span>
    </form>
</body>
</html>
