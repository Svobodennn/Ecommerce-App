<?php

namespace Core;

class Starter
{
    public $router;
    public $db;
    public $request;
    public $view;
    public $migrationCheck = false;

    public function __construct()
    {
        $this->router = new \Bramus\Router\Router();
        $this->db = new Database();
        $this->request = new Request();
        $this->view = new View();

        $stmt = $this->db->connect->query("SHOW TABLES LIKE 'migration_check'");
        if (!$stmt->rowCount() > 0) {
            $this->migrationCheck();
        }
    }

    public function migrationCheck()
    {
        $migrations = new Migration($this->db->connect);
        $migrations->run();

        $seeder = new Seeder($this->db->connect);
        $seeder->seed();
        $this->migrationCheck = true;
    }
}