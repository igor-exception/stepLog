<?php
namespace APP;

class User
{
    private $name;
    private $email;
    private $birth;
    private $password;
    
    public function __construct($name, $email, $birth, $password, $password_confirmation)
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->setBirth($birth);
        $this->setPassword($password, $password_confirmation);
    }

    private function setName($name)
    {
        $name = trim(preg_replace('/\s+/', ' ',$name));
        if(strlen($name) <= 3 || strlen($name) > 150) {
            throw new \Exception('Nome deve ser entre 4 e 150 caracteres');
        }
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }

    private function setEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception('Email inválido');
        }

        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    private function setBirth($birth)
    {
        list($year, $month, $day) = explode('-', $birth);
        if(!checkdate($month, $day, $year)) {
            throw new \Exception('Data de nascimento inválida');
        }
        $this->birth = $birth;
    }
    
    public function getBirth()
    {
        return $this->birth;
    }

    private function setPassword($password, $password_confirmation)
    {
        if($password != $password_confirmation) {
            throw new \Exception('Senha e confirmação de senha não são a mesma');
        }

        if(strlen($password) <= 5) {
            throw new \Exception('Senha precisa ser maior que 5 caracteres');
        }

        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword()
    {
        return $this->password;
    }
}