<?php

require_once 'common.php';

$connection = databaseConnection();

// I take the data that is inside myCart from session
$myCart = isset($_SESSION['myCart']) ? $_SESSION['myCart'] : [];

// initialise fields errors
$nameField_error = '';
$addressFieldError = '';

// initialise the input fields
$inputData = [];
$inputData['nameField'] = $inputData['addressField'] = $inputData['commentsField'] = '';

// I add the product the cart and the cookie retain it
if (isset($_POST['idProduct'])) {
    unset($myCart[intval($_POST['idProduct'])]);
    $_SESSION['myCart'] = $myCart;
} elseif (isset($_POST['nameField']) && $_POST['addressField']) {
    $inputData['nameField'] = $_POST['nameField'];
    if (strlen($inputData['nameField']) < 5 || strlen($inputData['nameField']) > 18) {
        $nameField_error = 'The name should be between 5 and 18 characters.';
    }

    $inputData['addressField'] = strip_tags($_POST['addressField']);
    if (!filter_var($inputData['addressField'], FILTER_VALIDATE_EMAIL)) {
        $addressFieldError = 'Invalid email address';
    }

    $inputData['commentsField'] = strip_tags($_POST['commentsField']);
    if ($nameField_error == '' && $addressFieldError == '') {
        $_SESSION['inputData'] = $inputData;
        header('Location: cart_mail.php');
        die();
    }
}

// in items are the list with all products
$items = extractProducts($connection, $myCart, INSIDE_CART);

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="http://localhost/Test/frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Cart Page') ?> </title>
</head>
<body>

<table id="contentTable">
    <tbody>
    <?php for ($i = 0; $i < count($items); $i++): ?>
        <tr class="elementOfTable">
            <td>
                <img class="phoneImage" src="<?= $items[$i]['image_path'] ?>">
            </td>
            <td>
                <?= $items[$i]['title'] ?><br>
                <?= $items[$i]['description'] ?><br>
                <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
            </td>
            <td>
                <form method="post" action="cart.php">
                    <input type="hidden" name="idProduct" value="<?= $items[$i]['id'] ?>">
                    <button type="submit" class="linkButton"> <?= translate('Remove') ?> </button>
                </form>
            </td>
        </tr>
        <br>
    <?php endfor; ?>
    <form action="cart.php" method="POST">
        <tr>
            <td>
                <input class="inputType" type="text" name="nameField"
                       placeholder="<?= translate('Name') ?>"
                       value="<?= $inputData['nameField'] ?>">
                <span class="errorField">* <?= translate($nameField_error) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input class="inputType" type="text" name="addressField"
                       placeholder="<?= translate('Contact deatails') ?>"
                       value="<?= $inputData['addressField'] ?>">
                <span class="errorField">* <?= translate($addressFieldError) ?></span>
            </td>
        </tr>
        <tr>
            <td>
            <textarea id="commentsSection" class="inputType" type="text" name="commentsField"
                      placeholder="<?= translate('Comments') ?>">
                <?= $inputData['commentsField'] ?>
            </textarea>
            </td>
        </tr>
        <tr>
            <td>
                <a href="index.php"><?= translate('Go to index') ?></a>
                <input type="submit" name="submitButton" value="Checkout">
            </td>
        </tr>
    </form>
    </tbody>
</table>
</body>
</html>
