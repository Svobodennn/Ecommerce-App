<?php

namespace App\Model;

use Core\BaseModel;
use Core\Session;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class ModelCart extends BaseModel
{
// TEK ÜRÜNÜN BİLGİLERİNİ ALMA
    public function getProduct($data)
    {
        extract($data);

        $result = $this->db->query("SELECT * FROM products WHERE id = $id");
        return $result ?? false;
    }
//  TÜM ÜRÜNLERİ VE BİLGİLERİNİ ALMA
    public function getProducts()
    {

        $result = $this->db->query("SELECT * FROM products", TRUE);
        return $result ?? false;
    }
// TEK ÜRÜN STOK BİLGİSİNİ ALMA
    public function getStock($id)
    {
        $result = $this->db->query("SELECT stock_quantity FROM products WHERE id = $id");

        return $result ? $result['stock_quantity'] : 0;
    }
// SEPETE EKLEME
    public function addToCart($data)
    {
        extract($data);
        $stock = $this->getStock($id);
        $product = $this->getProduct($id);

        if (!$product) {
            return false;
        }

        if ($stock <= 0) {
            return false;
        }

        if (isset($id)) {
            // var olan cookieyi ekliyoruz yoksa baştan oluşturuyoruz
            if (isset($_COOKIE['cart'])) {
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
// SEPETTEN AZALTMA
    public function decreaseFromCart($data)
    {
        extract($data);
        $stock = $this->getStock($id);

        if ($stock <= 0) {
            return false;
        }

        if (isset($id)) {
            // var olan cookieyi ekliyoruz yoksa baştan oluşturuyoruz
            if (isset($_COOKIE['cart'])) {
                $cart = json_decode($_COOKIE['cart'], true);
            } else {
                $cart = [];
            }


            // ürünü sepetten eksilt
            $cart[$id] = isset($cart[$id]) ? $cart[$id] - 1 : 0;

            // anında erişebilmek için session'a atıyoruz
            $_SESSION['cart'] = $cart;


            // httponly true yaparak xss açıklarına karşı önlem alıyoruz
            setcookie('cart', json_encode($cart), time() + (86400 * 5), "/", '', true, true); // 5 gün süreyle geçerli bir cookie
            return true;
        } else {
            return false;
        }
    }
// SEPET BİLGİSİNİ ALMA
    public function getCartList()
    {

        if (isset($_COOKIE['cart'])) {
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
                    'quantity' => $quantity,
                    'price' => $productDetails['price'],
                    'total_price' => $productDetails['price'] * $quantity,
                    'total_stock' => $productDetails['stock_quantity']
                ];
            }
            return $cartDetails;
        } else return false;
    }
// SEPETTEN ÜRÜN SİLME
    public function removeFromCart($data)
    {
        $product = $this->getProduct($data);
        extract($data);

        if (isset($product)) {
            if (isset($_COOKIE['cart'])) {
                // sepet bilgisini alma
                $cart = json_decode($_COOKIE['cart'], true);


                if (isset($cart[$id])) {
                    // Sepetten ürün kaldır
                    unset($cart[$id]);

                    // anında erişim için session
                    $_SESSION['cart'] = $cart;

                    // cookie'yi güncelle
                    setcookie('cart', json_encode($cart), time() + (86400 * 5), "/", '', true, true);

                    return true;
                } else return false;
            } else return false;
        } else return false;

    }
// KUPON KONTROLÜ
    public function checkCoupon($data)
    {
        extract($data);

        // aktif kuponu buluyoruz
        $stmt = $this->db->connect->prepare('Select * from coupons where title= :title AND status= :status');
        $stmt->execute([
            'title' => $coupon,
            'status' => 'a'
        ]);

        $couponExists = $stmt->fetch(\PDO::FETCH_ASSOC);

        // kupon varsa sessiona koyuyoruz
        if ($couponExists){
            _sessionSet('coupon',$coupon);
            return true;
        } else {
            return false;
        }

    }
// TOPLAM TUTARA GÖRE İNDİRİM KONTROLLERİ
    public function getSummary($totalPrice)
    {
        $totalPrice = round($totalPrice, 2);

        if ($totalPrice > 3000) {
            // %25
            $discountAmount = ($totalPrice * 25) / 100;
            $discountedTotal = $totalPrice - $discountAmount;
            $formattedDiscountedTotal = round($discountedTotal, 2);

            return ['bonus_product' => true, 'summary' =>$formattedDiscountedTotal];

        } elseif ($totalPrice > 2000) {
            // %20
            $discountAmount = ($totalPrice * 20) / 100;
            $discountedTotal = $totalPrice - $discountAmount;
            $formattedDiscountedTotal = round($discountedTotal, 2);

            return ['summary' => $formattedDiscountedTotal];

        } elseif ($totalPrice > 1500) {
            // %15
            $discountAmount = ($totalPrice * 15) / 100;
            $discountedTotal = $totalPrice - $discountAmount;
            $formattedDiscountedTotal = round($discountedTotal, 2);

            return ['summary' => $formattedDiscountedTotal];

        } elseif ($totalPrice > 1000) {
            // %10
            $discountAmount = ($totalPrice * 10) / 100;
            $discountedTotal = $totalPrice - $discountAmount;
            $formattedDiscountedTotal = round($discountedTotal, 2);

            return ['summary' => $formattedDiscountedTotal];
        } else {
            // ÜRÜN 1000TL ÜZERİNDE DEĞİLSE KUPON İNDİRİM SAĞLAMAZ
            return false;
        }


    }
// SİPARİŞ İÇİN İSİM OLUŞTURMA
    public function generateTitle($lastInserId, $userId)
    {
        $curdate = date('YmdHis');
        return "KAH" . $lastInserId . $userId . $curdate;
    }

    // SİPARİŞ OLUŞTURMA
    public function insertToOrders($userId, $totalPrice, $coupon, $summary, $bonus_product = false)
    {
        $products = $this->getProducts();
        // eğer 3000tl üzeriyse bonus ürün kazanır
        if ($bonus_product){
            for ($i = 0; $i <= sizeof($products); $i++){
                // ürünler arasından stokta olan bir tane seç
                if ($products[$i]['stock_quantity'] > 0 ){
                    $bonus_product = $products[$i]['id'];
                    break;
                }
            }
            // sayısını 1 eksilt
            $stmt = $this->db->connect
                ->prepare("UPDATE products 
                SET stock_quantity = stock_quantity-1
                WHERE id = :id");

            $stmt->execute(['id' => $bonus_product]);
            // ve aşağıdaki queryde idsini yerleştir.
        }

        $stmt = $this->db->connect->prepare('INSERT INTO orders 
                SET title= :title,
                user_id= :user_id,
                created_date= :created_date,
                status= :status,
                total= :total,
                coupon= :coupon,
                summary= :summary,
                bonus_product= :bonus_product');

        $result = $stmt->execute([
            'title' => 'title',
            'user_id' => $userId,
            'created_date' => date('Y-m-d H:i:s'), // şu anki tarih ve zaman
            'status' => 'a',
            'total' => $totalPrice,
            'coupon' => $coupon,
            'summary' => $summary,
            'bonus_product' => $bonus_product
        ]);

        if ($result) {
            // unique bir isim koymak için:
            $lastInsertId = $this->db->connect->lastInsertId();
            $title = $this->generateTitle($lastInsertId, $userId);

            $stmt = $this->db->connect->prepare('UPDATE orders 
                    SET title= :title WHERE id= :id');

            $stmt->execute([
                'id' => $lastInsertId,
                'title' => $title
            ]);

            return true;

        } else
            return false;
    }

    // SİPARİŞ OLUŞTURMA İÇİN KUPON VE TUTAR KONTROLLERİ
    function putOrder($data)
    {
        extract($data);
        $userId = _session('id');
        $coupon = _session('coupon');

        // her ürünün fiyatlarını toplayarak toplam fiyatı elde ediyoruz
        $totalPrice = 0;
        foreach ($data as $product) {
            $totalPrice += $product['total_price'];
        }

        // kupon varsa gerçekleşecek işlemler
        if (isset($coupon) && $coupon) {


            // kuponla kullanıcıyı ilişkilendir ve kupon aktifliğini pasif yap
            $stmt = $this->db->connect->prepare('UPDATE coupons SET user_id= :user_id, status= :status WHERE title= :title');
            $continue = $stmt->execute([
                'user_id' => $userId,
                'status' => 'p',
                'title' => $coupon
            ]);

            // eğer kupon girme işlemi başarısız olursa hata ver
            if (!$continue){
                return false;
            }

            // toplam fiyata göre elde edilecek indirimi hesapla
            $summary = $this->getSummary($totalPrice);

            // eğer toplam tutar 1000tlden büyükse sipariş oluştur. 1000tlden küçükse hata ver ve sayfaya tekrar yönlendir
            if (isset($summary['bonus_product'])){
                //sipariş oluştur
                $result = $this->insertToOrders($userId,$totalPrice,$coupon,$summary['summary'],true);
                return $result ?? false;
            }
            if (isset($summary['summary'])) {
                //sipariş oluştur
                $result = $this->insertToOrders($userId,$totalPrice,$coupon,$summary['summary']);

                return $result ?? false;
            } else {
                return ['status' => 'Total<1000'];
            }
        } else {
            $cargoPrice = 54.99;
            // toplam fiyat 500tlden düşükse kargo ücretini tahsil et
            if ($totalPrice < 500) {
                $summary = $totalPrice + $cargoPrice;
                $result = $this->insertToOrders($userId,$totalPrice,$coupon,$summary);

                if ($result) return true;
                return $result ?? false;

            } else {
                // toplam fiyat 500den yüksekse kargo ücretini alma
                $summary = $totalPrice;
                $result = $this->insertToOrders($userId,$totalPrice,$coupon,$summary);

                return $result ?? false;
            }
        }
    }

    // SİPARİŞİ VERİLEN ÜRÜNLERİ STOKTAN DÜŞME VE VERİTABANINDA SİPARİŞ İLE İLİŞKİLENDİRME
    function putProducts($data)
    {
        extract($data);

        // Stok kontrolü yaptık
        $stmt = $this->db->connect
            ->prepare("UPDATE products 
                SET stock_quantity = CASE WHEN stock_quantity >= :quantity 
                THEN stock_quantity - :quantity 
                ELSE stock_quantity END 
                WHERE id = :id");

        $result = $stmt->execute(['quantity' => $quantity,
            'id' => $id]);

        if ($result) {
            $lastInsertId = $this->db->query('select id from orders where id =(SELECT max(id) FROM orders)'); // where user id= session[id]

            $stmt = $this->db->connect
                ->prepare('INSERT INTO product_orders 
                        SET product_id= :product_id,
                        quantity= :quantity,
                        order_id= :order_id');
            $newResult = $stmt->execute([
                'product_id' => $id,
                'quantity' => $quantity,
                'order_id' => $lastInsertId['id']
            ]);

            if ($newResult) {
                return $newResult ?? false;
            }
        }
    }


    // ANA SİPARİŞ FONKSİYONU VE KONTROLLER
    public function finalizeOrder()
    {
        // sepetteki ürünleri cookie'den aldık
        $productsCart = $this->getCartList();
        // veritabanundaki ürünleri aldık
        // ölçeklenebilirlik için cookieden gelen id ile ürün çekilebilir
        // ve sessiondaki sepet bilgisiyle kontrol edilebilir
        // $product = $this->getProduct(['id' => $cookieItemId])
        $productsDatabase = $this->getProducts();

        if (!$productsCart || !$productsDatabase){
            return false;
        }

        $productExists = false;
        // her bir ürünü kontrol ediyoruz
        foreach ($productsCart as $value) {
            // veritabanındaki her ürünle eşleştiriyoruz
            foreach ($productsDatabase as $product) {
                // sepetteki ürünle veritabanındaki ürünün idleri karşılaştırıyoruz
                if ($value['id'] == $product['id']) {
                    // Eğer veritabanında böyle bir ürün varsa stoğunu kontrol ediyoruz
                    if ($value['quantity'] <= $product['stock_quantity']) {
                        $productExists = true;
                        break;
                    } else {
                        $productExists = false;
                        break;
                    }
                }
            }
            // Eğer ürün yoksa veya yeterli stok yoksa döngüden çıkıyoruz
            if (!$productExists) {
                break;
            }
        }


        if ($productExists) {

            $orderPut = $this->putOrder($productsCart);
            // eğer 1000tl altında kupon kullanılmışsa hata mesajını döndürür
            if (isset($orderPut['status'])) {
                return $orderPut;

                // işlemlerin başarısz olması durumunda false döndür
            } elseif (!$orderPut){
                return false;

            } else {
                // order oluşturulmuşsa ürünleri products_order'a koy ve orders ile ilişkilendir
                foreach ($productsCart as $product) {
                        $result = $this->putProducts($product);
                }
                // eğer tüm ürünler başarıyla işlenmişse sepeti boşalt
                if ($result){

                    unset($_COOKIE['cart']);
                    setcookie('cart', '', time() - 3600, '/');

                    return true;
                }
            }

        } else return false;

    }

    public function sendMail(){

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp-mail.outlook.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->CharSet = 'utf-8'; // türkçe karakter
            $mail->Username   = 'melioducard@hotmail.com';                     //SMTP username
            $mail->Password   = 'Kertemeyenkele16';                               //SMTP password
            $mail->SMTPSecure = 'STARTTLS'; //PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('melioducard@hotmail.com', 'Kahve Dükkanı Siparişiniz alındı');
            $mail->addAddress(_session('email'), _session('name').' '._session('surname'));     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Kahve Dükkanı Siparişiniz alındı';
            $mail->Body    = 'siparişi görüntüle: '.'<a href="'._link('user/orders').'">Tıkla!</a>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
        } catch (Exception $e) {
        }
    }

}
