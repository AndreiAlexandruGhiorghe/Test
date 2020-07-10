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
// from inside or outside the cart(index.php, cart.php)
function extractProducts($connection, $myCart, $typeOfProduct): array
{
    if ($typeOfProduct == INSIDE_CART) {
        $partOfTheQuery = 'SELECT * FROM products WHERE id IN ';
    } elseif ($typeOfProduct == OUTSIDE_CART) {
        $partOfTheQuery = 'SELECT * FROM products WHERE id NOT IN ';
    }

    // the query
    if (count($myCart)) {
        $queryString = $partOfTheQuery .
            '(' .
            implode(', ', array_fill(0, count($myCart), '?')) .
            ');';

        $items = query($connection, $queryString, array_keys($myCart));
    } else {
        if ($typeOfProduct == OUTSIDE_CART) {
            $queryString = 'SELECT * FROM products;';
            // the interogation to database
            $items = query($connection, $queryString, array_keys($myCart));
        } else {
            // that means I need the products that are inside the cart
            // no items I need
            $items = [];
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