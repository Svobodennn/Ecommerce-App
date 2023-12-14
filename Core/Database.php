<?php

namespace Core;

class Database
{
    public $connect;

    public function __construct()
    {
        try {
            // MySql serverine bağlan
            $this->connect = new \PDO('mysql:host=' . HOST, DB_USER, DB_PASSWORD);
            $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Veritabanını oluştur
            $this->createDatabase();

            // Veritabanına balan
            $this->connect = new \PDO('mysql:host=' . HOST . ';dbname=' . DB . ';', DB_USER, DB_PASSWORD);
            $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function createDatabase()
    {
        // SQL statement to create the database
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB . " CHARACTER SET utf8 COLLATE utf8_general_ci";

        // Execute the SQL statement
        $this->connect->exec($sql);

    }
    public function query($sql, $multi = false)
    {
        if ($multi == false) {
            return $this->connect->query($sql, \PDO::FETCH_ASSOC)->fetch() ?? [];
        } else {
            return $this->connect->query($sql, \PDO::FETCH_ASSOC)->fetchAll() ?? [];
        }
    }
    public function remove($sql)
    {
        return $this->connect->query($sql) ?? false;
    }
}