<?php
require_once 'config.php';

// the initialisation of the translation variable
$json_file = fread(fopen(constant('TRANSLATION_FILE'),'r'), filesize(constant('TRANSLATION_FILE')));
$translation = json_decode($json_file, true);
unset($json_file);

function query($connection, $query, $params): array
{
    // the query has "?" as a placeholder for params
    $stmt = $connection->prepare($query);
    $stmt->execute($params);
    $response = $stmt->fetchAll();

    return $response;
}

// database connection
function databaseConnection(): PDO {
    return new PDO(
        'mysql:host=' . constant('SERVER_NAME') . ';dbname=' . constant('DB_NAME'),
        constant('USERNAME'),
        constant('PASSWORD')
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

