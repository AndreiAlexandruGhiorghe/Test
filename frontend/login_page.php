<!DOCTYPE html>
<html>
<head>
    <title>Login page</title>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
</head>
<body>
<form action="login.php" method="POST">
    <input type="text" class="input_type" name="username_field" placeholder="<?= translate('Username') ?>" value="<?= translate($username_field) ?>">
    <span class="error_field">* <?= translate($username_field_error) ?></span>
    <br>
    <input type="password" class="input_type" name="password_field" placeholder="<?= translate('Password') ?>" value="<?= translate($password_field) ?>">
    <span class="error_field">* <?= translate($password_field_error) ?></span>
    <br>
    <input type="submit" class="input_type_login" name="submit_button" value="<?= translate('Login')?>">
    <span class="error_field"> <?= translate($login_failed_message) ?> </span>
</form>
</body>
</html>