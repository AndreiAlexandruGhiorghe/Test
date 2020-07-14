<?php

require_once 'common.php';

$connection = databaseConnection();

// I take the data that is inside myCart from session
$myCart = isset($_SESSION['myCart']) ? $_SESSION['myCart'] : [];

// initialise fields errors
$inputErrors = [];

// initialise the input fields
$inputData = [
    'nameField' => '',
    'addressField' => '',
    'commentsField' => '',
];

// I add the product the cart and the cookie retain it
if (isset($_POST['idProduct'])) {
    unset($myCart[intval($_POST['idProduct'])]);
    $_SESSION['myCart'] = $myCart;
} elseif (isset($_POST['nameField']) && isset($_POST['addressField'])) {
    $inputData['nameField'] = $_POST['nameField'];
    if (strlen($inputData['nameField']) < 5 || strlen($inputData['nameField']) > 18) {
        $inputErrors['nameFieldError'] = 'The name should be between 5 and 18 characters.';
    }

    $inputData['addressField'] = strip_tags($_POST['addressField']);
    if (!filter_var($inputData['addressField'], FILTER_VALIDATE_EMAIL)) {
        $inputErrors['addressFieldError'] = 'Invalid email address';
    }

    $inputData['commentsField'] = strip_tags($_POST['commentsField']);
    if (!count($inputErrors)) {
        ob_start();
        include('cart_mail.php');
        $htmlPage = ob_get_clean();
        // I use "" because otherwise when I send them on email, \r\n are saw as plain text
        $headers = 'From: ' . SENDER_ADDRESS . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";

        $dateTime = date('Y-m-d H-i-s');
        // inserting data in order_details table
        $result = query(
            $connection,
            'INSERT INTO order_details (creation_date, name, address, comments)
             VALUES (?, ?, ?, ?);',
            [
                $dateTime,
                $inputData['nameField'],
                $inputData['addressField'],
                $inputData['commentsField'],
            ]
        );

        // get the id from the last inserted row
        $orderLastId = $connection->lastInsertId();

        // insert into order_products the products from the current order
        foreach (array_keys($myCart) as $idProduct) {
            $result = query(
                    $connection,
                    'INSERT INTO order_products (id_order, id_product, quantity) VALUES (?, ?, ?);',
                    [
                            $orderLastId,
                            $idProduct,
                            $myCart[$idProduct]
                    ]
            );
        }
        // sending the mail
        mail($inputData['addressField'], 'Your Cart', $htmlPage, $headers);

        // decreasing the number of products from database
        $queryString = 'UPDATE products SET inventory = CASE ';
        $params = [];
        foreach ($myCart as $productId => $productQuantity) {
            $queryString .= 'WHEN id = ? THEN inventory - ? ';
            // adding the params at the param's array
            $params[] = $productId;
            $params[] = $productQuantity;
        }
        $queryString .= 'END WHERE id IN (' . implode(', ', array_fill(0, count($myCart), '?'))  . ');';
        $params = array_merge($params, array_keys($myCart));

        $result = query(
            $connection,
            $queryString,
            $params
        );

        // empty the cart
        $_SESSION['myCart'] = [];

        // redirect to index page with the cart empty
        header('Location: index.php');
        die();
    }
}

// in items are the list with all products
$items = extractProducts($connection, $myCart, INSIDE_CART);
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
                    <?= $myCart[$items[$i]['id']]?> <?= translate('products') ?><br>
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
                    <input
                            class="inputType"
                            type="text"
                            name="nameField"
                            placeholder="<?= translate('Name') ?>"
                            value="<?= $inputData['nameField'] ?>"
                    >
                    <span class="errorField"> <?=
                        isset($inputErrors['nameFieldError']) ?
                            '* ' . translate($inputErrors['nameFieldError']) : ''
                        ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <input
                            class="inputType"
                            type="text"
                            name="addressField"
                            placeholder="<?= translate('Contact deatails') ?>"
                            value="<?= $inputData['addressField'] ?>"
                    >
                    <span class="errorField">
                        <?= isset($inputErrors['addressFieldError']) ? '* ' . translate($inputErrors['addressFieldError']) : '' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                <textarea
                        id="commentsSection"
                        class="inputType"
                        type="text"
                        name="commentsField"
                        placeholder="<?= translate('Comments') ?>"
                >
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
