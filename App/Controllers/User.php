<?php

namespace App\Controllers;

use App\Model\ModelUser;
use Core\BaseController;
use Core\Session;

class User extends BaseController
{

    public function Index()
    {
        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');

        $data['user'] = Session::getAllSession();

        echo $this->view->load('user/index',compact('data'));
    }
    public function EditProfile()
    {
        $data = $this->request->post();

        if (!$data['userName']){
            $status = 'error';
            $title = 'Oops!';
            $msg = 'İsim boş olamaz';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        if (!$data['userSurname']){
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Soyisim boş olamaz';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        if (!$data['userEmail']){
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Email boş olamaz';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }

        $model = new ModelUser();
        $result = $model->editProfile($data);

        if ($result){
            $status = 'success';
            $title = 'Başarılı';
            $msg = 'Profil güncellendi';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        else{
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Beklenmedik bir hata meydana geldi, lütfen sayfayı yenileyip tekrar deneyin.';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
    }
    public function ChangePassword()
    {
        $data = $this->request->post();

        if (!$data['password']){
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Lütfen mevcut şifrenizi girin';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        if (!$data['newPassword'] || !$data['newPasswordAgain']){
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Lütfen yeni şifrenizi girin';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }

        if ($data['newPassword'] != $data['newPasswordAgain']){
            $status = 'error';
            $title = 'Oops!';
            $msg = "Yeni şifreler uyuşmuyor";
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }

        $model = new ModelUser();
        $result = $model->changePassword($data);

        if ($result){
            $status = 'success';
            $title = 'Başarılı';
            $msg = 'Şifre güncellendi';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        else{
            $status = 'error';
            $title = 'Oops!';
            $msg = 'Beklenmedik bir hata meydana geldi, lütfen sayfayı yenileyip tekrar deneyin.';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
    }
    public function showOrders(){

        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');

        $model = new ModelUser();
        $data['orders'] = $model->getOrders();

        echo $this->view->load('user/orders',compact('data'));
    }

    public function OrderDetails($id){

        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');

        $model = new ModelUser();
        $data['details'] = $model->getOrderDetails($id);

        echo $this->view->load('user/orderDetails',compact('data'));
    }

    public function PrintDetails($id){

        $model = new ModelUser();
        $userCheck = $model->userCheck($id);

        // eğer kullanıcının böyle bir sipariş idsi yok ise anasayfaya gönder
        if (!$userCheck){
            redirect('');
        }

        $data['details'] = $model->getOrderDetails($id);
        echo $this->view->load('user/orderPrint',compact('data'));

    }

    public function cancelOrder(){
        $data = $this->request->post();

        $model = new ModelUser();
        $userCheck = $model->userCheck($data['order_id']);

        if (!$userCheck){
            echo json_encode(['status' => 'error', 'title' => 'Bir hata meydana geldi', 'msg' => 'Lütfen sayfayı yenileyip tekrar deneyin']);
        }

        $result = $model->cancelOrder($data['order_id']);

        if ($result){
            echo json_encode(['status' => 'success', 'title' => 'Başarılı', 'msg' => 'Sipariş iptal edildi', 'redirect' => _link('user/orders')]);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Bir hata meydana geldi', 'msg' => 'Lütfen sayfayı yenileyip tekrar deneyin']);
        }
    }

}