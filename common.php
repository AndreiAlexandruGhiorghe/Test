<?php
require_once 'config.php';

session_start();

// the initialisation of the translation variable
$json_file = (fopen(TRANSLATION_FILE, 'r') !== false) ? fopen(TRANSLATION_FILE, 'r') : 0;

$json_file_size = (filesize(TRANSLATION_FILE) !== false) ? filesize(TRANSLATION_FILE) : 0;

$json_file_content = (
    $json_file != 0
    && fread($json_file, $json_file_size) !== false
) ? fread($json_file, $json_file_size) : '';

$translation = json_decode($json_file_content, true);
$translation = ($translation !== null && $translation !== false) ? $translation : [];

fclose($json_file);

function query($connection, $query, $params): array
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

function question_marks($nr_of_quotes): string
{
    $return_string = '(';
    $return_string .= implode(', ', array_fill(0, $nr_of_quotes, '?'));
    $return_string .= ')';

    return $return_string;
}

// extract_products it's used whenever I need to list products
// from inside or outside the cart(index.php, cart.php)
function extract_products($connection, $my_cart, $type_of_product): array
{
    if ($type_of_product == INSIDE_CART) {
        $part_of_the_query = 'SELECT * FROM products WHERE id IN ';
    } elseif ($type_of_product == OUTSIDE_CART) {
        $part_of_the_query = 'SELECT * FROM products WHERE id NOT IN ';
    }

    // the query
    if (count($my_cart)) {
        $query_string = $part_of_the_query . question_marks(count($my_cart)) . ';';
        $items = query($connection, $query_string, array_keys($my_cart));
    } else {
        if ($type_of_product == OUTSIDE_CART) {
            $query_string = 'SELECT * FROM products;';
            // the interogation to database
            $items = query($connection, $query_string, array_keys($my_cart));
        } else {
            // that means I need the products that are inside the cart
            // no items I need
            $items = [];
        }
    }

    return $items;
}