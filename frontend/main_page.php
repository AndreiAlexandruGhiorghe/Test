<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title>Main Page</title>
</head>
<body>

<table id="content_table">
    <?php for ($i = 0; $i < count($items); $i++): ?>
        <?php if (!isset($my_cart[intval($items[$i]['id'])])): ?>
            <tr class="element_of_table">
                <td>
                    <img class="phone_image" src="<?= '.' . $items[$i]['image_path'] ?>">
                </td>
                <td>
                    <?= translate($items[$i]['title']) ?><br>
                    <?= translate($items[$i]['description']) ?><br>
                    <?= translate($items[$i]['price']) ?> <?= translate('lei') ?><br>
                </td>
                <td>
                    <a href="<?= 'index.php?id_product=' . $items[$i]['id'] ?>"><?= translate('Add') ?></a>
                </td>

            </tr>
            <br>
        <?php endif; ?>
    <?php endfor; ?>

</table>
<?php ?>
<br>
<br>
<a href="cart.php"><?= translate('Go to cart') ?></a>
</body>
</html>
