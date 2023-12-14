<?php

namespace App\Model;

use Core\BaseModel;
use Core\Session;

class ModelAuth extends BaseModel
{
    public function userLogin($data){
        extract($data);

        $stmt = $this->db->connect->prepare("Select * from users where users.email = :email");
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        $password = $data['password'];
        if ($user){
            if (password_verify($password, $user['password'])) {
                Session::setSession('login', true);
                Session::setSession('id', $user['id']);
                Session::setSession('name',$user['name']);
                Session::setSession('surname',$user['surname']);
                Session::setSession('email',$user['email']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}