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

    public function addToCart($data){
        extract($data);

        if (isset($id)){
            // var olan cookieyi ekliyoruz yoksa baştan oluşturuyoruz
            $cart = json_decode($_COOKIE['cart'], true) ?? [];

            // yeni ürünü sepete ekle
            $cart[$id] = isset($cart[$id]) ? $cart[$id] + 1 : 1;

            // httponly true yaparak xss açıklarına karşı önlem alıyoruz
            setcookie('cart', json_encode($cart), time() + (86400 * 5), "/", '', true, true); // 5 gün süreyle geçerli bir cookie

            return true;
        } else {
            return false;
        }
    }
}