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

    public function checkUser($email){

        $stmt = $this->db->connect->prepare("select * from users where email= :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        // eğer kayıtlı email bulursa
        if ($result){
            return false;
        }
        return true;
    }

    public function registerUser($data){
        extract($data);

        $stmt = $this->db->connect->prepare("INSERT INTO users SET
            name= :name,
            surname= :surname,
            email= :email,
            password= :password");

        $result = $stmt->execute([
            'name' => $name,
            'surname' => $surname,
            'email' => $emailRegister,
            'password' => password_hash($passwordRegister,PASSWORD_DEFAULT)
        ]);

        if ($result){
            return true;
        }

        return false;
    }
}