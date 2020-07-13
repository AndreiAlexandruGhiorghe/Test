<?php

require_once 'common.php';

// check if the user is logged in, if he itsn't then I redirect him to login.php
checkAuthorization();

// the connection with server
$connection = databaseConnection();

// whenever I execute a query like select...where id = 0 from the database will return []
//instead of a error
$idProductEdit = (!isset($_GET['idProductEdit'])) ? 0 : intval($_GET['idProductEdit']);

$productDetails = query(
        $connection,
        'SELECT * FROM products WHERE id = ?;',
        [$idProductEdit]
);

// admin input
$inputData = [
    'titleField' => (isset($productDetails[0]['title']) && isset($idProductEdit))
                        ? $productDetails[0]['title']
                        : '',
    'descriptionField' => (isset($productDetails[0]['description']) && isset($idProductEdit))
                                ? $productDetails[0]['description']
                                : '',
    'priceField' => (isset($productDetails[0]['price']) && isset($idProductEdit))
                            ? $productDetails[0]['price']
                            : '',
    'imageNameField' => (isset($productDetails[0]['image_path']) && isset($idProductEdit))
                                ? pathToName($productDetails[0]['image_path'])
                                : '',
];

// the errors from the admin's input
$inputError = [];

if (isset($_POST['submitButton'])) {
    // validate fields: title, description and price
    if (isset($_POST['titleField']) && $_POST['titleField']) {
        $inputData['titleField'] = $_POST['titleField'];
    } else {
        $inputError['titleFieldError'] = 'Please enter a title for product';
    }
    if (isset($_POST['descriptionField']) && $_POST['descriptionField']) {
        $inputData['descriptionField'] = $_POST['descriptionField'];
    } else {
        $inputError['descriptionFieldError'] = 'Please enter a description for product';
    }
    if (isset($_POST['priceField'])) {
        $inputData['priceField'] = $_POST['priceField'];

        if (!is_numeric($_POST['priceField']) || !intval($_POST['priceField'])) {
            $inputError['priceFieldError'] = 'Please enter a natural number as price for product';
        }
    }

    // validate the files input
    if (isset($_FILES['fileField']) && $_FILES['fileField']['tmp_name']) {
        $inputData['imageLocation'] = $_FILES['fileField']['tmp_name'];
        $inputData['imageNameField'] = $_FILES['fileField']['name'];
    } elseif (!isset($productDetails[0]['image_path'])) {
        // if I don't have something in $_FILES and I don't have an old image set, then
        // it means I have no image for the product
        $inputError['imageFileFieldError'] = 'Please choose an image for the product';
    }

    // if I have no errors util now
    if (!count($inputError)) {
        // idProductEdit is equal 0 when there is no product for editing
        if (!$idProductEdit) {
            $imagePath = 'images/' . time() . $inputData['imageNameField'];
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
            // check if my actual image is the old image from db
            if (isset($productDetails[0]['image_path']) && $inputData['imageNameField'] == pathToName($productDetails[0]['image_path'])) {
                $queryString = 'UPDATE products SET title = ?, description = ?, price = ? WHERE id = ?';
                $paramQuery = [
                    $inputData['titleField'],
                    $inputData['descriptionField'],
                    intval($inputData['priceField']),
                    $idProductEdit,
                ];
            } else {
                $queryString = 'UPDATE products 
                                SET title = ?, description = ?, price = ?, image_path = ? 
                                WHERE id = ?';
                $imagePath = 'images/' . time() . $inputData['imageNameField'];
                $paramQuery = [
                    $inputData['titleField'],
                    $inputData['descriptionField'],
                    intval($inputData['priceField']),
                    $imagePath,
                    $idProductEdit,
                ];
            }
            // the execution of the query
            $response = query(
                $connection,
                $queryString,
                $paramQuery
            );

            // when the admin chooses a new image we have to delete the old one
            if (!(isset($productDetails[0]['image_path'])
                && $inputData['imageNameField'] == pathToName($productDetails[0]['image_path'])
            )) {
                move_uploaded_file($inputData['imageLocation'], $imagePath);
                // delete the old image
                if (isset($productDetails[0]['image_path']) && file_exists($productDetails[0]['image_path'])) {
                    unlink($productDetails[0]['image_path']);
                }
            }
            header('Location: products.php');
            die();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="http://localhost/Test/frontend/style.css">
    <meta charset="UTF-8">
    <title> <?= translate('Admin\'s page') ?> </title>
    <script>
        // I need this function when I want to save the name of the image
        // and tha function it changes the default value of the label to
        // the actual value of the input file
        function changeLabel() {
            var asta = document.getElementById('inputFileId').value;
            var aux = asta.split('\\')[asta.split('\\').length - 1];
            document.getElementById('labelId').innerText = aux;
        }
    </script>
</head>
<body onload="LoadValue();">
    <table id="contentTable">
        <tbody>
            <form
                    action="product.php?idProductEdit=<?= isset($idProductEdit) ? $idProductEdit : '' ?>"
                    method="POST"
                    enctype="multipart/form-data">
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
                <tr>
                    <td>
                        <label for="inputFileId" id="labelId" name="labelId">
                                <?= ($inputData['imageNameField'])
                                    ? $inputData['imageNameField']
                                    : translate('Choose an Image: Click Here!'); ?>
                        </label>
                        <input onchange="changeLabel()" type="file" id="inputFileId" style="display:none" name="fileField">
                        <span class="errorField">
                            <?= isset($inputError['imageFileFieldError'])
                                ? translate($inputError['imageFileFieldError'])
                                : '' ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="products.php">
                            <?= translate('Products') ?>
                        </a>
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
