<?php

namespace App\Model;

use Core\BaseModel;
use Core\Session;

class ModelHome extends BaseModel
{
    public function getEverything()
    {
        $data = $this->db->query(
            'SELECT
                    p.id as id,
                    p.title AS product_title,
                    p.description AS product_description,
                    p.price AS product_price,
                    p.stock_quantity AS stock_quantity,
                    c.title AS category_title,
                    o.title AS origin,
                    r.title as roast_level,
                    GROUP_CONCAT(fn.title) AS flavor_notes
                FROM
                    products p
                JOIN
                    categories c ON p.category_id = c.id
                JOIN
                    origins o ON p.origin_id = o.id
                JOIN
                    roast_levels r ON p.roast_level = r.id
                LEFT JOIN
                    product_flavor_notes pfn ON p.id = pfn.product_id
                LEFT JOIN
                    flavor_notes fn ON pfn.flavor_note_id = fn.id
                GROUP BY
                    p.id;
                ', true);
        return $data;
    }

    public function getProduct($data){
        extract($data);

        $result = $this->db->query("SELECT * FROM products WHERE id = $id");
        return $result ? $result : false;
    }

    public function getStock($id){
        $result = $this->db->query("SELECT stock_quantity FROM products WHERE id = $id");

        return $result ? $result['stock_quantity'] : 0;
    }
    public function addToCart($data){
        extract($data);
        $stock = $this->getStock($id);

        if ($stock <= 0) {
            return false;
        }

        if (isset($id)){
            // var olan cookieyi ekliyoruz yoksa baştan oluşturuyoruz
            if (isset($_COOKIE['cart'])){
                $cart = json_decode($_COOKIE['cart'], true);
            } else {
                $cart = [];
            }


            // yeni ürünü sepete ekle
            $cart[$id] = isset($cart[$id]) ? $cart[$id] + 1 : 1;

            // anında erişebilmek için session'a atıyoruz
            $_SESSION['cart'] = $cart;


            // httponly true yaparak xss açıklarına karşı önlem alıyoruz
            setcookie('cart', json_encode($cart), time() + (86400 * 5), "/", '', true, true); // 5 gün süreyle geçerli bir cookie
            return true;
        } else {
            return false;
        }
    }

    public function getCartList(){

        if (isset($_COOKIE['cart'])){
            $cart = $_COOKIE['cart'];
            $cartDetails = [];

            foreach (json_decode($cart, true) as $productId => $quantity) {
                // id ye göre veritabanından ürün detaylarını alma
                $productDetails = $this->getProduct(['id' => $productId]);
                // ürün detaylarının ve toplam sayının dönüşe eklenmesi
                $cartDetails[] = [
                    'id' => $productId,
                    'title' => $productDetails['title'],
                    'description' => $productDetails['description'],
                    'quantity' => $quantity
                ];
            }
            return $cartDetails;
        }

//        if (isset($_SESSION['cart'])){
//            $cart = $_SESSION['cart'];
//            $cartDetails = [];
//
//            foreach ($cart as $productId => $quantity) {
//                // id ye göre veritabanından ürün detaylarını alma
//                $productDetails = $this->getProduct(['id' => $productId]);
//                // ürün detaylarının ve toplam sayının dönüşe eklenmesi
//                $cartDetails[] = [
//                    'id' => $productId,
//                    'title' => $productDetails['title'],
//                    'description' => $productDetails['description'],
//                    'quantity' => $quantity
//                ];
//            }
//
//            return $cartDetails;
//        }
    }
}