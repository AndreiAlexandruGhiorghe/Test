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

<table id="content_table">
    <tbody>
    <?php for ($i = 0; $i < count($items); $i++): ?>
            <tr class="element_of_table">
                <td>
                    <img class="phone_image" src="<?= $items[$i]['image_path'] ?>">
                </td>
                <td>
                    <?= $items[$i]['title'] ?><br>
                    <?= $items[$i]['description'] ?><br>
                    <?= $items[$i]['price'] ?> <?= translate('lei') ?><br>
                </td>
                <td>
                    <form method="post" action="product.php">
                        <input type="hidden" name="id_product_edit" value="<?= $items[$i]['id'] ?>">
                        <button type="submit" class="link_button"> <?= translate('Edit') ?> </button>
                    </form>
                </td>
                <td>
                    <form method="post" action="products.php">
                        <input type="hidden" name="id_product_delete" value="<?= $items[$i]['id'] ?>">
                        <button type="submit" class="link_button"> <?= translate('Delete') ?> </button>
                    </form>
                </td>
            </tr>
            <br>
    <?php endfor; ?>
    <tr>
        <td>
            <form method="post" action="product.php">
                <input type="hidden" name="id_product_edit" value="<?= $items[$i]['id'] ?>">
                <button type="submit" class="link_button"> <?= translate('Add') ?> </button>
            </form>
        </td>
        <td>
            <form method="post" action="login.php">
                <input type="hidden" name="id_product_delete" value="<?= $items[$i]['id'] ?>">
                <button type="submit" class="link_button"> <?= translate('Logout') ?> </button>
            </form>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
