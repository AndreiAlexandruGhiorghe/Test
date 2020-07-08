<?php

require_once 'common.php';

// check if the user is logged in, if he itsn't then I redirect him to login.php
checkAuthorization();

// if admin chooses to go to products I have first to unset some variables
if (isset($_POST['goToProducts'])) {
    header("Location: products.php");
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

if (isset($_POST['idProductEdit'])) {
    $idProductEdit = intval($_POST['idProductEdit']);
}
if (isset($_POST['oldImagePath'])) {
    $oldImagePath = $_POST['oldImagePath'];
}

if (isset($_POST['editButton'])) {
    $productEditInfo = query(
        $connection,
        'SELECT title, description, price, image_path FROM products WHERE id = ?;',
        [$idProductEdit]
    );
    $inputData = [
        'titleField' => $productEditInfo[0]['title'],
        'descriptionField' => $productEditInfo[0]['description'],
        'priceField' => $productEditInfo[0]['price'],
        'imageNameField' => '',
    ];
    $oldImagePath = $productEditInfo[0]['image_path'];
}

if (isset($_POST['submitButton'])) {
    // validate fields: title, description and price
    $data = validateFields(['inputData' => $inputData, 'post' => $_POST]);
    $inputData = $data['inputData'];
    $inputError = $data['inputError'];

    if (isset($_FILES['imageFileField']) && $_FILES['imageFileField']['tmp_name']) {
        $inputData['imageLocation'] = $_FILES['imageFileField']['tmp_name'];
        $inputData['imageName'] = $_FILES['imageFileField']['name'];
    } else {
        $inputError['imageFileFieldError'] = 'Please choose an image for the product';
    }

    if (!count($inputError)) {
        // idProductEdit is equal 0 when there is no product for editing
        if (!$idProductEdit) {
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
            header('Location: products.php');
            die();
        } else {
            $imagePath = 'images/' . time() . $inputData['imageName'];
            $response = query(
                $connection,
                'UPDATE products SET title = ?, description = ?, price = ?, image_path = ? WHERE id = ?',
                [
                    $inputData['titleField'],
                    $inputData['descriptionField'],
                    intval($inputData['priceField']),
                    $imagePath,
                    $idProductEdit,
                ]
            );
            move_uploaded_file($inputData['imageLocation'], $imagePath);
            // delete the old image\
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            header('Location: products.php');
            die();
        }
    }
}



var_dump($_POST);
var_dump($_GET);
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
                <input
                        type="hidden"
                        name="idProductEdit"
                        value="<?= isset($idProductEdit) ? $idProductEdit : '' ?>"
                >
                <input
                        type="hidden"
                        name="oldImagePath"
                        value="<?= isset($oldImagePath) ? $oldImagePath : '' ?>"
                >
                <tr>
                    <td>
                        <input
                                type="text"
                                name="titleField"
                                placeholder="<?= translate('Title') ?>"
                                value="<?= $inputData['titleField'] ?>"
                        >
                        <span class="errorField">
                            <?= isset($inputError['titleFieldError'])
                                ? translate($inputError['titleFieldError'])
                                : '' ?>
                        </span>
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
                        <span class="errorField">
                            <?= isset($inputError['descriptionFieldError'])
                                ? translate($inputError['descriptionFieldError'])
                                : '' ?>
                        </span>
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
                        <span class="errorField">
                            <?= isset($inputError['priceFieldError'])
                                ? translate($inputError['priceFieldError'])
                                : '' ?>
                        </span>
                    </td>
                </tr>
<!--                <tr>-->
<!--                    <td>-->
<!--                        <input type="file" name="imageFileField">-->
<!--                        <span class="errorField">-->
<!--                            --><?//= isset($inputError['imageFileFieldError'])
//                                ? translate($inputError['imageFileFieldError'])
//                                : '' ?>
<!--                        </span>-->
<!--                    </td>-->
<!--                </tr>-->
                <?php var_dump($_FILES) ?>
                <tr>
                    <td><button onclick="this.children[0].click()" name="button" >
                            <?php var_dump($_FILES) ?>
                            <input type="file" name="imageFileFieldd" style="display: none;">


                        </button><span class="errorField">
                                                    </span></td>
                    <td>
                        <button type="submit" name="goToProducts" class="linkButton">
                            <?= translate('products') ?>
                        </button>
                    </td>
                    <td>
                        <button type="submit" name="submitButton">
                            <?= translate('Save') ?>
                        </button>
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
</body>
</html>
