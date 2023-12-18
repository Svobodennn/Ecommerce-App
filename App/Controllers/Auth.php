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
    public function Register(){
        $data = $this->request->post();


        if (!$data['name'] || !$data['surname'] || !$data['emailRegister'] || !$data['passwordRegister'] || !$data['passwordRepeat']){
            echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Lütfen tüm bilgileri doldurunuz.']);
            exit();
        }

        if (strlen($data['name']) > 60 || strlen($data['surname']) > 60 || strlen($data['emailRegister']) > 60 || strlen($data['passwordRegister']) > 60){
            echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Lütfen bilgilerinizi düzgün bir şekilde girdiğinizden emin olunuz.']);
            exit();
        }
            // rakam ve özel karakter kontrolü
        $pattern = '/^[a-zA-ZÇçĞğİıÖöŞşÜü\s]+$/u';
        if (!preg_match($pattern,$data['name']) || !preg_match($pattern,$data['surname'])){
            echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Lütfen bilgilerinizi düzgün bir şekilde girdiğinizden emin olunuz.']);
            exit();
        }

        if ($data['passwordRegister'] != $data['passwordRepeat']){
            echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Şifreler birbiriyle uyuşmuyor.']);
            exit();
        }

        $model = new ModelAuth();

        $checkUSer = $model->checkUser($data['emailRegister']);

        if ($checkUSer){
            $result = $model->registerUser($data);

            if ($result){
                echo json_encode(['status' => 'success', 'title' => 'Başarılı', 'msg' => 'Başarıyla kayıt oldunuz. Giriş yapmaya yönlendiriliyorsunuz.','redirect' => _link('login')]);
            } else {
                echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Bir hata meydana geldi. Lütfen daha sonra tekrar deneyin']);
            }
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Hata!', 'msg' => 'Bu kullanıcı zaten kayıtlı!']);
        }

    }

}