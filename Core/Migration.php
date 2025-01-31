<?php
namespace Core;

class Migration
{
    protected $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $this->createMigrationCheckTable();
        $this->createUsersTable();
        $this->createOriginsTable();
        $this->createFlavorNotesTable();
        $this->createCategoriesTable();
        $this->createRoastLevelsTable();
        $this->createProductsTable();
        $this->createOrdersTable();
        $this->createCouponsTable();
        $this->createProductFlavorNotesTable();
        $this->createProductOrders();
        // Gerektiğinde migration ekleyin
    }

    private function createMigrationCheckTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS migration_check (
                id INT AUTO_INCREMENT PRIMARY KEY,
                executed BOOLEAN NOT NULL DEFAULT 0
            )
        ');
    }

    private function createUsersTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(60) NOT NULL,
                surname VARCHAR(60) NOT NULL,
                email VARCHAR(60) NOT NULL,
                password VARCHAR(255) NOT NULL
            )
        ');

    }
    private function createOriginsTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS origins (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(30) NOT NULL
            )
        ');

    }
    private function createFlavorNotesTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS flavor_notes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(30) NOT NULL
            )
        ');

    }
    private function createProductFlavorNotesTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS product_flavor_notes (
                product_id INT,
                flavor_note_id INT,
                PRIMARY KEY (product_id, flavor_note_id),
                FOREIGN KEY (product_id) REFERENCES products(id),
                FOREIGN KEY (flavor_note_id) REFERENCES flavor_notes(id)
            )
        ');

    }
    private function createCategoriesTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(30) NOT NULL
            )
        ');

    }
    private function createRoastLevelsTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS roast_levels (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(60) NOT NULL
            )
        ');

    }
    private function createProductsTable()
    {
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(60) NOT NULL,
                category_id int NOT NULL,
                description VARCHAR(60) NOT NULL,
                origin_id int NOT NULL,
                roast_level int NOT NULL,
                stock_quantity int NOT NULL,
                price decimal(6,2) NOT NULL,
                image varchar(100)
            )
        ');

    }

    private function createOrdersTable() {
        $this->db->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(60) NOT NULL,
            user_id INT NOT NULL,
            created_date DATETIME NOT NULL,
            status enum('a', 'p') NOT NULL DEFAULT 'a',
            total DECIMAL(9,2) NOT NULL,
            coupon varchar(13),
            summary DECIMAL(9,2) NOT NULL,
            bonus_product int
        )
    ");
    }

    private function createCouponsTable(){
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS coupons (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(13) NOT NULL UNIQUE,
                status enum('a','p') NOT NULL DEFAULT 'a',
                user_id int,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ");
    }
    private function createProductOrders(){
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS product_orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                order_id INT NOT NULL,
                quantity INT NOT NULL,
                FOREIGN KEY (product_id) REFERENCES products(id),
                FOREIGN KEY (order_id) REFERENCES orders(id)
            )
        ");
    }

    // gerektiğinde tablo ekleyin
}
