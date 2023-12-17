<?php

namespace App\Controllers;


use App\Model\ModelCart;
use Core\BaseController;
use Core\Request;

class Cart extends BaseController
{
    public function Index()
    {
        // Sayfa yüklendiğinde güvenlik önlemi olarak kuponu sıfırlıyoruz
        _sessionSet('coupon', NULL);

        $model = new ModelCart();

        $data['cartList'] = $model->getCartList();

        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');
        $data['header'] = $this->view->load('static/header');
        $data['footer'] = $this->view->load('static/footer');
        echo $this->view->load('cart/index', compact('data')); // ['data' => $data]
    }

    public function AddToCart()
    {
        $data = $this->request->post();
        $model = new ModelCart();

        $result = $model->addToCart($data);
        $cartList = $model->getCartList();

        if ($result) {
            echo json_encode(['status' => 'success', 'title' => 'Ürün sepete eklendi', 'msg' => 'Başarılı', 'cart' => $cartList]);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Ürün sepete eklenemedi', 'msg' => 'Bir Hata Meydana Geldi']);
        }

    }

    public function decreaseFromCart()
    {

        $data = $this->request->post();
        $model = new ModelCart();

        $result = $model->decreaseFromCart($data);

        if ($result) {
            echo json_encode(['status' => 'success', 'title' => 'Ürün sepete eklendi', 'msg' => 'Başarılı']);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Ürün sepete eklenemedi', 'msg' => 'Bir Hata Meydana Geldi']);
        }

    }

    public function removeFromCart()
    {
        $data = $this->request->post();
        $model = new ModelCart();

        $result = $model->removeFromCart($data);

        if ($result) {
            echo json_encode(['status' => 'success', 'title' => 'Ürün sepetten kaldırıldı', 'msg' => 'Başarılı']);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Ürün sepetten kaldırılamadı', 'msg' => 'Bir Hata Meydana Geldi']);
        }

    }

    public function checkCoupon()
    {
        $data = $this->request->post();

        $model = new ModelCart();
        $result = $model->checkCoupon($data);

        if ($result) {
            _sessionSet('coupon', $data['coupon']);
            echo json_encode(['status' => 'success', 'title' => 'Geçerli kupon', 'msg' => 'Başarılı']);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Geçersiz kupon', 'msg' => 'Bir Hata Meydana Geldi']);
        }
    }

    public function finalizeOrder()
    {

        $model = new ModelCart();
        $result = $model->finalizeOrder();

        if (isset($result['status'])) {
            echo json_encode(['status' => 'error', 'msg' => "Kupon girmek için sipariş tutarı 1000TL'den fazla olmalıdır.", 'title' => 'Başarılı', 'redirect' => _link('cart')]);
        } elseif ($result) {
            echo json_encode(['status' => 'success', 'msg' => 'Sipariş başarıyla oluşturuldu', 'title' => 'Başarılı', 'redirect' => _link('user/orders')]);
            $model->sendMail();
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Sipariş verilemedi', 'title' => 'Bir Hata Meydana Geldi']);
        }
    }
}