<?php
require_once 'common.php';
// check if the user is logged in, if he itsn't then I redirect him to login.php
if (!isset($_SESSION['username']) || $_SESSION['username'] != ADMIN_CREDENTIALS['USERNAME']) {
    header('Location: login.php');
    die();
}

// the connection with server
$connection = databaseConnection();

// admin input
$inputData = [
    'titleField' => '',
    'descriptionField' => '',
    'priceField' => '',
    'imageNameField' => '',
];

// the errors from the admin's input
$inputError = [];

if (!isset($_SESSION['actionType'])) {
    // checking if editButton was pressed
    if (isset($_POST['editButton']) && isset($_POST['idProductEdit'])) {
        $_SESSION['idProductEdit'] = intval($_POST['idProductEdit']);
        $_SESSION['actionType'] = EDIT_ACTION;

        // taking the data from database
        $productEditInfo = query(
                $connection,
                'SELECT title, description, price, image_path FROM products WHERE id = ?;',
                [$_SESSION['idProductEdit']]
        );
        var_dump($productEditInfo);
    } elseif (isset($_POST['addButton'])) {
        // checked: if addButton was pressed
        $_SESSION['actionType'] = ADD_ACTION;
    }
}

// checking if the add button was pressed or edit button or none
if (isset($_SESSION['actionType'])) {
    if ($_SESSION['actionType'] == ADD_ACTION) {
        if (isset($_POST['submitButton'])) {
            checkingProductFields($inputData, $inputError, $_POST);
            if (isset($_FILES['imageFileField']) && $_FILES['imageFileField']['tmp_name']) {
                $inputData['imageLocation'] = $_FILES['imageFileField']['tmp_name'];
                $inputData['imageName'] = $_FILES['imageFileField']['name'];
            } else {
                $inputError['imageFileFieldError'] = 'Please choose an image for the product';
            }
            if (!count($inputError)) {
                $imagePath = 'images/' . time() . $inputData['imageName'];
                $response = query(
                    $connection,
                    'INSERT INTO products (title, description, price, image_path) VALUES (?, ?, ?, ?)',
                    [
                        $inputData['titleField'],
                        $inputData['descriptionField'],
                        intval($inputData['priceField']),
                        $imagePath,
                    ]
                );
                move_uploaded_file($inputData['imageLocation'], $imagePath);
                unset($_FILES['imageFileField']);
            }
        }
    }
    if ($_SESSION['actionType'] == EDIT_ACTION) {

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="http://localhost/Test/frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Admin\'s page') ?> </title>
</head>
<body>
<table id="contentTable">
    <tbody>
    <form action="product.php" method="POST" enctype="multipart/form-data">
        <tr>
            <td>
                <input
                        type="text"
                        name="titleField"
                        placeholder="<?= translate('Title') ?>"
                        value="<?= $inputData['titleField'] ?>"
                >
                <span class="errorField"> <?= translate(
                        isset($inputError['titleFieldError']) ? $inputError['titleFieldError'] : ''
                    ) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input
                        type="text"
                        name="descriptionField"
                        placeholder="<?= translate('Description') ?>"
                        value="<?= $inputData['descriptionField'] ?>"
                >
                <span class="errorField"> <?= translate(
                        isset($inputError['descriptionFieldError']) ? $inputError['descriptionFieldError'] : ''
                    ) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input
                        type="text"
                        name="priceField"
                        placeholder="<?= translate('Price') ?>"
                        value="<?= $inputData['priceField'] ?>"
                >
                <span class="errorField"> <?= translate(
                        isset($inputError['priceFieldError']) ? $inputError['priceFieldError'] : ''
                    ) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <input type="file" name="imageFileField" value=" <?=
                    isset($_FILES['imageFileField']['tmp_name']) ? $_FILES['imageFileField']['tmp_name'] : ''
                ?> ">
                <span class="errorField"> <?= translate(
                        isset($inputError['imageFileFieldError']) ? $inputError['imageFileFieldError'] : ''
                    ) ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <a href="products.php"><?= translate('Products') ?></a>
            </td>
            <td>
                    <button type="submit" name="submitButton"> <?= translate('Save') ?></button>
            </td>
        </tr>
    </form>
    </tbody>
</table>
</body>
</html>
