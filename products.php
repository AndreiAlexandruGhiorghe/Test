<?php
require_once 'common.php';

// check if the user is logged in, if he itsn't then I redirect him to login.php
checkAuthorization();

// if the user wants to logout then
// I first must delete from session username and after I redirect him to login.php
if (isset($_POST['logoutButton'])) {
    doLogout();
}

$connection = databaseConnection();

if (isset($_POST['deleteItem'])) {
    // checking if the item exists
    $result = query(
            $connection,
            'SELECT image_path FROM products WHERE id = ?',
            [$_POST['idProductDelete']]
    );
    $response = query(
        $connection,
        'DELETE FROM products WHERE id = ?',
        [$_POST['idProductDelete']]
    );
    // deleting the image from directory with checking
    if (isset($result[0]['image_path']) && file_exists($result[0]['image_path'])) {
        unlink($result[0]['image_path']);
    }
    header('Location: products.php');
    die();
}

$items = query($connection, 'SELECT * FROM products;');
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
                        <?= $items[$i]['inventory'] ?> <?= translate('products') ?><br>
                    </td>
                    <td>
                        <a href="product.php?idProductEdit=<?= $items[$i]['id'] ?>">
                            <?= translate('Edit') ?>
                        </a>
                    </td>
                    <td>
                        <form method="POST" action="products.php">
                            <input type="hidden" name="idProductDelete" value="<?= $items[$i]['id'] ?>">
                            <button type="submit" class="linkButton" name="deleteItem">
                                <?= translate('Delete') ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <br>
            <?php endfor; ?>
            <tr>
                <td>
                    <a href="product.php" class="linkButton">
                        <?= translate('Add') ?>
                    </a>
                </td>
                <td>
                    <form method="post" action="products.php">
                        <button type="submit" class="linkButton" name="logoutButton">
                            <?= translate('Logout') ?>
                        </button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
