<?php
require_once 'config.php';

session_start();

// the initialisation of the translation variable
$jsonFile = (fopen(TRANSLATION_FILE, 'r') !== false) ? fopen(TRANSLATION_FILE, 'r') : 0;

$jsonFileSize = (fileSize(TRANSLATION_FILE) !== false) ? fileSize(TRANSLATION_FILE) : 0;

$jsonFileContent = (
    $jsonFile != 0
    && fread($jsonFile, $jsonFileSize) !== false
) ? fread($jsonFile, $jsonFileSize) : '';

$translation = json_decode($jsonFileContent, true);
$translation = ($translation !== null && $translation !== false) ? $translation : [];

fclose($jsonFile);


function query($connection, $query, $params = []): array
{
    // the query has "?" as a placeholder for params
    $stmt = $connection->prepare($query);
    if ($stmt !== false) {
        if ($stmt->execute($params)) {
            $response = $stmt->fetchAll();
            // if fetchAll returns false then we will return a empty array
            $response = ($response !== false) ? $response : [];

            return $response;
        }
    }
    // in case of errors that response will be returned
    return [];
}

// database connection
function databaseConnection(): PDO
{
    return new PDO(
        'mysql:host=' . SERVER_NAME . ';dbname=' . DB_NAME,
        USERNAME,
        PASSWORD
    );
}

// Translation function
function translate($string): string
{
    global $translation;
    if (isset($translation[$string])) {
        return $translation[$string];
    }
    return $string;
}

// extractProducts it's used whenever I need to list products
// from inside the cart or all products from db
function extractProducts($connection, $myCart, $typeOfProduct): array
{
    if ($typeOfProduct == ALL_PRODUCTS) {
        $items = query($connection, 'SELECT * FROM products');
    } elseif ($typeOfProduct == INSIDE_CART) {
        // the query
        if (count($myCart)) {
            $queryString = 'SELECT * FROM products WHERE id IN (' .
                implode(', ', array_fill(0, count($myCart), '?')) .
                ');';

            $items = query($connection, $queryString, array_keys($myCart));
        } else {
            // that means I need the products that are inside the cart
            // no items I need
            $items = [];
        }
    } elseif ($typeOfProduct == OUTSIDE_CART) {
        // the query
        if (count($myCart)) {
            $queryString = 'SELECT * FROM products WHERE id NOT IN (' .
                implode(', ', array_fill(0, count($myCart), '?')) .
                ')';

            $params = array_keys($myCart);

            $queryString .= ' OR inventory > CASE ';
            foreach ($myCart as $idProduct => $quantity) {
                $queryString .= 'WHEN id = ? THEN ? ';
                $params[] = $idProduct;
                $params[] = $quantity;
            }
            $queryString .= ' END;';

            $items = query($connection, $queryString, $params);
        } else {
            // that means I need the products that are inside the cart
            // no items I need
            $items = query($connection, 'SELECT * FROM products WHERE inventory > 0;');
        }
    }

    return $items;
}

// check if the user is logged in, if he itsn't then I redirect him to login.php
function checkAuthorization()
{
    if (!isset($_SESSION['username']) || $_SESSION['username'] != ADMIN_CREDENTIALS['USERNAME']) {
        header('Location: login.php');
        die();
    }
}

function doLogout()
{
    unset($_SESSION['username']);
    header('Location: login.php');
    die();
}

function pathToName($path): string
{
    $aux = explode('/', $path);
    $aux = ($aux) ? $aux[count($aux) - 1] : '';

    return $aux;
}