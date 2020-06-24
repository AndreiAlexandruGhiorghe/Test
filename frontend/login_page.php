<!DOCTYPE html>
<html>
<head>
    <title>Login page</title>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
</head>
<body>
<form action="login.php" method="POST">
    <input type="text" class="input_type_login" name="username_field" placeholder="<?= translate('Username') ?>">
    <br>
    <input type="password" class="input_type_login" name="password_field" placeholder="<?= translate('Password') ?>">
    <br>
    <input type="submit" class="input_type_login" name="submit_button" value="<?= translate('Login')?>">
</form>
</body>
</html>