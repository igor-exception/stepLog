<?php
namespace APP;

class User
{
    private $name;
    private $email;
    private $birth;
    private $password;
    
    public function __construct($name, $email, $birth, $password)
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->setBirth($birth);
        $this->setPassword($password);
    }

    private function setName($name)
    {
        $name = trim(preg_replace('/\s+/', ' ',$name));
        if(strlen($name) <= 3 || strlen($name) > 150) {
            throw new \DomainException('Nome deve ser entre 4 e 150 caracteres');
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
            throw new \DomainException('Email inválido');
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
            throw new \DomainException('Data de nascimento inválida');
        }
        $this->birth = $birth;
    }
    
    public function getBirth()
    {
        return $this->birth;
    }

    private function setPassword($password)
    {
        if(strlen($password) < 8) {
            throw new \DomainException('Senha precisa ser maior ou igual a 8 caracteres');
        }

        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getPassword()
    {
        return $this->password;
    }
}