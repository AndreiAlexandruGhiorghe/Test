<?php
require_once 'common.php';

$connection = databaseConnection();

$items = query($connection, 'SELECT * FROM products;',[]);
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
                    <form method="post" action="product.php">
                        <input type="hidden" name="idProductEdit" value="<?= $items[$i]['id'] ?>">
                        <button type="submit" class="linkButton"> <?= translate('Edit') ?> </button>
                    </form>
                </td>
                <td>
                    <form method="post" action="products.php">
                        <input type="hidden" name="idProductDelete" value="<?= $items[$i]['id'] ?>">
                        <button type="submit" class="linkButton"> <?= translate('Delete') ?> </button>
                    </form>
                </td>
            </tr>
            <br>
    <?php endfor; ?>
    <tr>
        <td>
            <form method="post" action="product.php">
                <input type="hidden" name="idProductEdit" value="<?= $items[$i]['id'] ?>">
                <button type="submit" class="linkButton"> <?= translate('Add') ?> </button>
            </form>
        </td>
        <td>
            <form method="post" action="login.php">
                <input type="hidden" name="idProductDelete" value="<?= $items[$i]['id'] ?>">
                <button type="submit" class="linkButton"> <?= translate('Logout') ?> </button>
            </form>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
