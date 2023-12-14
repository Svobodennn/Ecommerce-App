<?php

namespace Core;
class Seeder
{
    protected $db;

    // JSON dosyasından verileri ayrı ayrı almak için değişkenler
    protected $jsonData;
    protected $uniqueCategories = [];
    protected $uniqueFlavorNotes = [];
    protected $uniqueRoastLevels = [];
    protected $uniqueOrigins = [];

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $jsonString = file_get_contents(BASEDIR.'/App/data/Case_Products_kahve.json');
        $this->jsonData = json_decode($jsonString, true);
    }

    private function getIdFromName($tableName, $columnName, $name) {
        $query = $this->db->prepare("SELECT id FROM {$tableName} WHERE {$columnName} = :name");
        $query->execute([':name' => $name]);
        return $query->fetchColumn();
    }

    private function recordExists($tableName, $conditionColumn, $conditionValue) {
        $stmt = $this->db->prepare("SELECT * FROM {$tableName} WHERE {$conditionColumn} = :conditionValue");
        $stmt->execute([':conditionValue' => $conditionValue]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function seed()
    {
        $this->getData();
        // Bunu kaldırmayın

        $this->seedUsers();
        $this->seedCategories();
        $this->seedOrigins();
        $this->seedRoastLevels();
        $this->seedFlavorNotes();
        $this->seedProducts();
        $this->seedProductFlavorNotes();
        $this->seedMigrationCheck();
        // Add more seed methods for other tables as needed
    }

    // json verilerinden veri ayıklandı.
    public function getData(){

        $jsonString = file_get_contents(BASEDIR.'/App/data/Case_Products_kahve.json');
        // json dosyasındaki tüm veriler alındı

        $jsonData = json_decode($jsonString, true);

        // her bir öge için
        foreach ($jsonData as $item) {
            // Categories (unique)
            // kategori id'sinin varlığını kontrol edip eğer yoksa uniqueCategories'e ekler.
            $categoryKey = $item['category_id'];
            if (!isset($this->uniqueCategories[$categoryKey])) {
                $this->uniqueCategories[$categoryKey] = [
                    'id' => $item['category_id'],
                    'title' => $item['category_title'],
                ];
            }

            // Flavor Notes
            // Flavor note'un varlığını ve array olup olmadığını kontrol eder, eğer varsa bir sonraki flavor note'a geçer
            // her note için $uniqueFlavorNotes'da olup olmadığını kontrol eder yoksa ekler
            if (isset($item['flavor_notes']) && is_array($item['flavor_notes'])) {
                foreach ($item['flavor_notes'] as $note) {
                    $flavorNoteKey = $note;
                    if (!isset($this->uniqueFlavorNotes[$flavorNoteKey])) {
                        $this->uniqueFlavorNotes[$flavorNoteKey] = [
                            'title' => $note,
                        ];
                    }
                }
            }

            // Roast Level
            // Aynı adımlar roast level için de yapılır
            $roastLevelKey = $item['roast_level'];
            if (!isset($this->uniqueRoastLevels[$roastLevelKey])) {
                $this->uniqueRoastLevels[$roastLevelKey] = [
                    'title' => $item['roast_level'],
                ];
            }

            // Origin
            // ve origin için de
            $originKey = $item['origin'];
            if (!isset($this->uniqueOrigins[$originKey])) {
                $this->uniqueOrigins[$originKey] = [
                    'title' => $item['origin'],
                ];
            }
        }
        $this->uniqueCategories = array_values($this->uniqueCategories);
        $this->uniqueFlavorNotes = array_values($this->uniqueFlavorNotes);
        $this->uniqueRoastLevels = array_values($this->uniqueRoastLevels);
        $this->uniqueOrigins = array_values($this->uniqueOrigins);
    }

    private function seedUsers()
    {
        $users = [
            ['Melih', 'Saraç', 'melih@example.com', password_hash('123123', PASSWORD_DEFAULT)]
            // Add more user data as needed
        ];
        foreach ($users as $user) {
            // Aynı kullanıcıyı tekrar kaydetmemek için var olup olmadığını kontrol ediyoruz
            $stmt = $this->db->prepare('select * from users where email=:email');
            $stmt->execute([':email' => $user[2]]);
            $userExists = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($userExists) {continue;}
            // Eğer aynı epostayla birisi varsa döngüye devam et

            $this->db->prepare('INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)')
                ->execute($user);
        }
    }

    private function seedOrigins(){
        $origins = $this->uniqueOrigins;

        foreach ($origins as $origin){
            $originExists = $this->recordExists('origins','title',$origin['title']);
            if ($originExists) {continue;}


            $this->db->prepare('INSERT INTO origins (title) VALUES (?) ')
                ->execute([$origin['title']]);
        }
    }

    private function seedMigrationCheck(){
        $executed = 1;
        $this->db->prepare('INSERT INTO migration_check (executed) VALUES (?)')
            ->execute([$executed]);
    }

    private function seedRoastLevels(){
        $roastLevels = $this->uniqueRoastLevels;

        foreach ($roastLevels as $roastLevel){

            $roastLevelExists = $this->recordExists('roast_levels','title',$roastLevel['title']);
            if ($roastLevelExists) {continue;}

            $this->db->prepare('INSERT INTO roast_levels (title) VALUES (?) ')
                ->execute([$roastLevel['title']]);
        }
    }
    private function seedFlavorNotes(){
        $flavorNotes = $this->uniqueFlavorNotes;

        foreach ($flavorNotes as $flavorNote){
            $flavorNoteExists = $this->recordExists('flavor_notes','title',$flavorNote['title']);

            if ($flavorNoteExists) {continue;}


            $this->db->prepare('INSERT INTO flavor_notes (title) VALUES (?) ')
                ->execute([$flavorNote['title']]);
        }
    }
    private function seedCategories(){
        $categories = $this->uniqueCategories;

        foreach ($categories as $category){

            $categoryExists = $this->recordExists('categories','title',$category['title']);

            if ($categoryExists) {continue;}

            $this->db->prepare('INSERT INTO categories (id,title) VALUES (?, ?) ')
                ->execute([$category['id'],$category['title']]);
        }
    }
    private function seedProducts(){
        $products = $this->jsonData;

        foreach ($products as $product){

            $productExists = $this->recordExists('products', 'id', $product['product_id']);
            if ($productExists) {continue;}

            $originId = $this->getIdFromName('origins', 'title', $product['origin']);
            $roastLevelId = $this->getIdFromName('roast_levels', 'title', $product['roast_level']);

//            $price = str_replace('.', ',', $product['price']);

            $this->db->prepare('INSERT INTO products (id, title, category_id, description, origin_id, roast_level, stock_quantity, price)
                    VALUES (:id, :title, :category_id, :description, :origin_id, :roast_level, :stock_quantity, :price)')
                ->execute([
                    ':id' => $product['product_id'],
                    ':title' => $product['title'],
                    ':category_id' => $product['category_id'],
                    ':description' => $product['description'],
                    ':origin_id' => $originId,
                    ':roast_level' => $roastLevelId,
                    ':stock_quantity' => $product['stock_quantity'],
                    ':price' => $product['price'],
                ]);

        }

    }

    private function seedProductFlavorNotes(){
        $products = $this->jsonData;
        foreach ($products as $product) {
            // Check if the product exists
            $productExists = $this->recordExists('products', 'id', $product['product_id']);
            if (!$productExists) {
                // If the product doesn't exist, you may want to handle this case (throw an exception, log, etc.)
                continue;
            }

            // Iterate through flavor notes for the product
            foreach ($product['flavor_notes'] as $flavorNoteTitle) {
                // Check if the flavor note title is provided in your JSON
                if (empty($flavorNoteTitle)) {
                    // Handle the case where flavor note title is missing or empty in your JSON
                    continue;
                }

                // Get the flavor note ID based on the title from the flavor_notes table
                $flavorNoteId = $this->getIdFromName('flavor_notes','title',$flavorNoteTitle);

                // Insert into the product_flavor_notes table
                $this->db->prepare('INSERT INTO product_flavor_notes (product_id, flavor_note_id) VALUES (:product_id, :flavor_note_id)')
                    ->execute([
                        ':product_id' => $product['product_id'],
                        ':flavor_note_id' => $flavorNoteId
                    ]);
            }

        }
    }

    // Diğer tablolar için de ihtiyaç halinde method ekleyin.
}
