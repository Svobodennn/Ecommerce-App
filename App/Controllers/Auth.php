<?php

namespace App\Controllers;


use App\Model\ModelAuth;
use Core\BaseController;
use Core\Migration;
use Core\Seeder;
use Core\Session;
use function mysql_xdevapi\getSession;

class Auth extends BaseController
{
    public function Index(){

        $data['form_link'] = _link('login');
        echo $this->view->load('auth/index',$data);
    }
    public function Login(){
        $data = $this->request->post();

        if (!$data['email']){
            $status = 'error';
            $title = 'Hata!';
            $msg = 'Lütfen geçerli bir Eposta giriniz';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
        if (!$data['password']){
            $status = 'error';
            $title = 'Hata!';
            $msg = 'Lütfen geçerli bir şifre giriniz';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }

        $model = new ModelAuth();
        $result = $model->userLogin($data);


        if ($result){
            $status = 'success';
            $title = 'Yay!';
            $msg = 'Signed in';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg, 'redirect' => _link()]);
            exit();
        } else {
            $status = 'error';
            $title = 'Hata!';
            $msg = 'Hatalı Eposta veya şifre. Lütfen tekrar deneyiniz.';
            echo json_encode(['status' => $status, 'title' => $title, 'msg' => $msg]);
            exit();
        }
    }
    public function Logout(){
        Session::removeSession();
        unset($_COOKIE['cart']);
        setcookie('cart', '', time() - 3600, '/');
        redirect('login');
    }

}