<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="frontend/style.css">
    <meta charset="UTF-8">
    <title>Main Page</title>
</head>
<body>

<table id="content_table">
    <?php for ($i = 0; $i < count($items); $i++):?>
        <tr class="element_of_table">
            <td>
                <img class="phone_image" src="<?='.' . $items[$i]['image_path'] ?>">
            </td>
            <td>
                <?=$items[$i]['title'] ?><br>
                <?=$items[$i]['description'] ?><br>
                <?=$items[$i]['price'] ?><br>
            </td>

        </tr>
        <br>
    <?php endfor; ?>
</table>
<?php ?>

</body>
</html>
