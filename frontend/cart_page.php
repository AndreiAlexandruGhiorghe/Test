<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title>Main Page</title>
</head>
<body>

<table id="content_table">
    <tbody>
        <?php for ($i = 0; $i < count($items); $i++): ?>
            <?php ?>
            <?php if (isset($my_cart[intval($items[$i]['id'])])): ?>
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
                        <a href="<?= 'cart.php?id_product=' . $items[$i]['id'] ?>"><?= translate('Remove') ?></a>
                    </td>
                </tr>
                <br>
            <?php endif; ?>
        <?php endfor; ?>
        <form action="cart.php" method="POST">
            <tr>
                <td>
                    <input class="input_type" type="text" name="name_field" placeholder="<?= translate('Name') ?>" value="<?= translate($name_field) ?>">
                    <span class="error_field">* <?= translate($name_field_error) ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="input_type" type="text" name="address_field" placeholder="<?= translate('Contact deatails')?>" value="<?= translate($address_field) ?>">
                    <span class="error_field">* <?= translate($address_field_error) ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <textarea id="comments_section" class="input_type" type="text" name="comments_field" placeholder="<?= translate('Comments') ?>"><?= translate($comments_field) ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="index.php"><?= translate('Go to index') ?></a>
                    <input type="submit" name="submit_button" value="Checkout">
                </td>
            </tr>
        </form>
    </tbody>
</table>
<?php ?>
</body>
</html>