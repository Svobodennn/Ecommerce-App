<?php

namespace App\Model;

use Core\BaseModel;
use Core\Session;

class ModelUser extends BaseModel
{
    public function editProfile($data)
    {
        extract($data);


        // duplicate email olmaması için önlem
        $stmt = $this->db->connect->prepare('select id from users where email= :email');
        $stmt->execute(['email' => $userEmail]);

        $idCheck = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($idCheck['id'] != _session('id')){
            return false;
        }
        //

        $user = $this->db->connect->prepare('update users set 
            users.name= :name,
            users.surname= :surname,
            users.email= :email
            where users.id= :id');

        $result = $user->execute([
            'name' => $userName,
            'surname' => $userSurname,
            'email' => $userEmail,
            'id' => Session::getSession('id')
        ]);

        if ($result) {
            Session::setSession('name', $userName);
            Session::setSession('surname', $userSurname);
            Session::setSession('email', $userEmail);
            return true;
        } else {
            return false;
        }
    }

    public function changePassword($data)
    {
        extract($data);

        $user = $this->db->connect->prepare('Select password from users where id= :id');
        $user->execute([
            'id' => _session('id')
        ]);

        $result = $user->fetch(\PDO::FETCH_ASSOC);


        if (!password_verify($password, $result['password'])) {
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Geçersiz şifre';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }

        $update = $this->db->connect->prepare('update users set password= :password where users.id= :id');
        $result = $update->execute([
            'password' => password_hash($newPassword,PASSWORD_DEFAULT),
            'id' => Session::getSession('id')
        ]);


        if ($result)
            return true;
        else
            return false;

    }
    public function getOrderDetails($order_id){
        $id = _session('id');
        $result = $this->db->query("
            SELECT
                o.id AS order_id,
                o.title AS order_title,
                o.created_date,
                o.status,
                o.summary,
                o.coupon,
                o.total,
                o.bonus_product,
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'product_id', p.id,
                        'product_title', p.title,
                        'product_description', p.description,
                        'category_title', c.title,
                        'origin_title', o2.title,
                        'flavor_notes', fn.flavor_notes,
                        'roast_level_title', r.title,
                        'quantity', po.quantity,
                        'product_total', po.quantity * p.price
                    )
                ) AS products
            FROM
                orders o
            JOIN
                product_orders po ON o.id = po.order_id
            JOIN
                products p ON po.product_id = p.id
            JOIN
                categories c ON p.category_id = c.id
            LEFT JOIN
                origins o2 ON p.origin_id = o2.id
            LEFT JOIN
                roast_levels r ON p.roast_level = r.id
            LEFT JOIN (
                SELECT
                    pfn.product_id,
                    GROUP_CONCAT(DISTINCT f.title) AS flavor_notes
                FROM
                    product_flavor_notes pfn
                JOIN
                    flavor_notes f ON pfn.flavor_note_id = f.id
                GROUP BY
                    pfn.product_id
            ) fn ON p.id = fn.product_id
            WHERE
                o.id = $order_id
                AND o.user_id = $id
            GROUP BY
                o.id;
", TRUE);

        // siparişe göre ürünleri gruplamak için sql json özelliğini kullandık.

        foreach ($result as $key => $value){
            // her bir siparişin ürünlerini alıyoruz
            $productsString = $result[$key]['products'];

           // json stringini alıp array'e dönüştürüyoruz
            $productArray = json_decode('[' . $productsString . ']', true);

            // products kısmını boşaltıp içine ürün arraylerimizi koyuyoruz
            $result[$key]['products'] = [];
            $result[$key]['products'] = array_merge($result[$key]['products'] + $productArray);

        }


        return $result;
    }
    public function getOrders(){
        $id = _session('id');
        $result = $this->db->query("
        SELECT
            o.id AS order_id,
            o.title AS order_title,
            o.status,
            COUNT(po.product_id) AS product_count,
            o.created_date,
            o.summary
        FROM
            orders o
        JOIN
            product_orders po ON o.id = po.order_id
        WHERE
            o.user_id = $id
        AND
            o.status = 'a'
        GROUP BY
            o.id;
", TRUE);

        return $result;




    }
    public function userCheck($order_id){
        $user_id = _session('id');
        $match = $this->db->query("select * from orders where user_id = $user_id AND id = $order_id", TRUE);

        if ($match){
            return true;
        } else return false;
    }

    public function cancelOrder($order_id){
        // siparişi bul ve status'unu pasif yap
        $stmt = $this->db->connect->prepare("UPDATE Orders SET status = 'p' WHERE id= :order_id");
        $result = $stmt->execute(['order_id' => $order_id]);

        if ($result){
            // siparişe ait ürünleri bul
            $products = $this->db->query("SELECT product_id, quantity FROM product_orders WHERE order_id = $order_id", TRUE);

            // her ürün için stoğa geri koy
            foreach ($products as $product){
                $productId = $product['product_id'];
                $quantity = $product['quantity'];

                $stmt = $this->db->connect->prepare("UPDATE products SET stock_quantity = stock_quantity + :quantity WHERE id= :productId");
                $result = $stmt->execute([
                    'quantity'=>$quantity,
                    'productId' =>$productId
                ]);


            } return $result;

        } return false;

    }
}