<?php
require_once 'config.php';

// the initialisation of the translation variable
$json_file = fopen(TRANSLATION_FILE, 'r');
$json_file_content = fread($json_file, filesize(TRANSLATION_FILE));
$translation = json_decode($json_file_content, true);
fclose($json_file);

function query($connection, $query, $params): array
{
    // the query has "?" as a placeholder for params
    $stmt = $connection->prepare($query);
    $stmt->execute($params);
    $response = $stmt->fetchAll();

    return $response;
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

function doc_type_html(): string
{
    return '<!DOCTYPE html>';
}

function extract_keys($assoc_array): array
{
    $keys = [];
    foreach ($assoc_array as $key => $value) {
        array_push($keys, $key);
    }

    return $keys;
}

function question_marks($nr_of_quotes): string
{

    $return_string = '(';
    for ($i = 0; $i < $nr_of_quotes - 1; $i++) {
        $return_string .= '?, ';
    }
    $return_string .= ($nr_of_quotes) ? '?)' : ')';

    return $return_string;
}

function extract_products($connection, $my_cart, $type_of_product): array
{
    if ($type_of_product == 'inside the cart') {
        $part_of_the_query = 'SELECT * FROM products WHERE id IN ';
    } elseif ($type_of_product == 'outside the cart') {
        $part_of_the_query = 'SELECT * FROM products WHERE id NOT IN ';
    }

    // the query
    if (count($my_cart)) {
        $query_string = $part_of_the_query . question_marks(count($my_cart)) .';';
    } else {
        $query_string = 'SELECT * FROM products;';
    }

    // the interogation to database
    $items = query($connection, $query_string, extract_keys($my_cart));

    return $items;
}