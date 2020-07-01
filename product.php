<?php
require_once 'common.php';
// check if the user is logged in, if he itsn't then I redirect him to login.php
if (!isset($_SESSION['username']) || $_SESSION['username'] != ADMIN_CREDENTIALS['username']) {
    header('Location: login.php');
    die();
}

// admin input
$inputData = [
    'titleField' => '',
    'descriptionField' => '',
    'priceField' => '',
    'imageNameField' => '',
];

if (isset($_POST['titleField'])) {
    $inputData['titleField'] = $_POST['titleField'];
}

if (isset($_POST['descriptionField'])) {
    $inputData['descriptionField'] = $_POST['descriptionField'];
}

if (isset($_POST['priceField'])) {
    $inputData['priceField'] = $_POST['priceField'];
}

// I save the file as a unic name composed by image name and timestamp
if (isset($_POST['imageNameField'])) {
    $inputData['imageNameField'] = $_POST['imageNameField'];
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
                    type="text" name="titleField" placeholder="<?= translate('Title') ?>" value="<?= $inputData['titleField'] ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="descriptionField" placeholder="<?= translate('Description') ?>"
                       value="<?= $inputData['descriptionField'] ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="priceField" placeholder="<?= translate('Price') ?>"
                       value="<?= $inputData['priceField'] ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input type="text"name="imageNameField" placeholder="<?= translate('Image') ?>"
                       value="<?= $inputData['imageNameField'] ?>">
                <input type="file" name="imageFileField">
            </td>
        </tr>
        <tr>
            <td>
                <a href="products.php"><?= translate('Products') ?></a>
            </td>
            <td>
                    <button type="submit" value="<>"> <?= translate('Save') ?></button>
            </td>
        </tr>
    </form>
    </tbody>
</table>
</body>
</html>
