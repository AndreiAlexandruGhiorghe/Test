<?php
require_once 'common.php';

// check if the user is logged in, if he itsn't then I redirect him to login.php
if (!isset($_SESSION['username']) || $_SESSION['username'] != ADMIN_CREDENTIALS['USERNAME']) {
    header('Location: login.php');
    die();
}

// if the user wants to logout then
// I first must delete from session username and after I redirect him to login.php
if (isset($_POST['logoutButton'])) {
    unset($_SESSION['username']);
    header('Location: login.php');
    die();
}

$connection = databaseConnection();

if (isset($_POST['deleteItem'])) {
    // checking if the item exists
    $result = query(
            $connection,
            'SELECT image_path FROM products WHERE id = ?',
            [$_POST['idProductDelete']],
    );
    if (isset($result[0]['image_path'])) {
        $response = query(
                $connection,
            'DELETE FROM products WHERE id = ?',
            [$_POST['idProductDelete']]
        );
        unlink($result[0]['image_path']);
    }
}

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
                        <button type="submit" class="linkButton" name="editButton">
                            <?= translate('Edit') ?>
                        </button>
                    </form>
                </td>
                <td>
                    <form method="post" action="products.php">
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
            <form method="post" action="product.php">
                <button type="submit" name="addButton" class="linkButton"> <?= translate('Add') ?> </button>
            </form>
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
