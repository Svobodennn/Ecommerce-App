<?php
namespace App\Controllers;


use App\Model\ModelHome;
use Core\BaseController;

class Home extends BaseController
{
    public function Index()
    {

        $model = new ModelHome();

        $data['navbar'] = $this->view->load('static/navbar');
        $data['sidebar'] = $this->view->load('static/sidebar');
        $data['header'] = $this->view->load('static/header');
        echo $this->view->load('home/index',compact('data')); // ['data' => $data]
    }
}