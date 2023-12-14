<?php
namespace App\Controllers;


use App\Model\ModelHome;
use Core\BaseController;
use Core\Request;

class Home extends BaseController
{
    public function Index()
    {

        $model = new ModelHome();

        $data['products'] = $model->getEverything();
        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');
        $data['header'] = $this->view->load('static/header');
        $data['footer'] = $this->view->load('static/footer');
        echo $this->view->load('home/index',compact('data')); // ['data' => $data]
    }

    public function AddToCart(){

        $data = $this->request->post();
        $model = new ModelHome();

        $result = $model->addToCart($data);

        if ($result){
            echo json_encode(['status' => 'success', 'title' => 'Ürün sepete eklendi', 'msg' => 'Başarılı']);
        } else {
            echo json_encode(['status' => 'error', 'title' => 'Ürün sepete eklenemedi', 'msg' => 'Bir Hata Meydana Geldi']);
        }

    }
}