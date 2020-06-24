<?php

//database connection
class DatabaseConnection
{
    private $connection;

    public function __construct($server_name, $username, $password, $db_name)
    {
        try {
            $this->connection = new PDO("mysql:host=$server_name;dbname=$db_name", $username, $password);
        } catch (Exception $e) {
            die("Can't connect to database");
        }
    }

    public function query($query, $params)
    {
        //the query has "?" as a placeholder for params
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        $response = $stmt->fetchAll();

        return $response;
    }
}

function translate($string)
{
    return $string;
}