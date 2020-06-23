<?php

//database connection
class DatabaseConnection
{
    private $connection;

    public function __construct($server_name, $username, $password, $db_name)
    {
        try {
            $this->connection = new PDO("mysql:host=$server_name;db=$db_name", $username, $password);
        } catch (Exception $e) {
            die("Can't connect to database");
        }
    }

    public function query($query, $params)
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        $response = $stmt->fetchAll();

        return $response;
    }
}