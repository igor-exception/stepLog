<?php
use PHPUnit\Framework\TestCase;
use APP\User;

class UserTest extends TestCase
{
    public function testCreateValidUser()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        $user = new User($name, $email, $birth, $password, $password_confirmation);
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($birth, $user->getBirth());
        $this->assertNotEquals($password, $user->getPassword());
    }

    public function testCreateValidUserWithUTF8Letters()
    {
        $name = "JOsÉ AntÔnio GONçAlves";
        $email = 'jose@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        $user = new User($name, $email, $birth, $password, $password_confirmation);
        $this->assertEquals("José Antônio Gonçalves", $user->getName());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($birth, $user->getBirth());
        $this->assertNotEquals($password, $user->getPassword());
    }

    public function testCreateUserWithInvalidName()
    {
        $name = 'Joh';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Nome deve ser entre 4 e 150 caracteres');
        $user = new User($name, $email, $birth, $password, $password_confirmation);
    }

    public function testCreateUserWithInvalidNameWithSpaces()
    {
        $name = '    J      h         ';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Nome deve ser entre 4 e 150 caracteres');
        $user = new User($name, $email, $birth, $password, $password_confirmation);
    }

    public function testCreateUserWithInvalidEmail()
    {
        $name = 'John Doe';
        $email = 'john@gmail';
        $birth = '1990-04-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Email inválido');
        $user = new User($name, $email, $birth, $password, $password_confirmation);
    }

    public function testCreateUserWithInvalidBirth()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-40-03';
        $password = '123a456b';
        $password_confirmation = '123a456b';

        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Data de nascimento inválida');
        $user = new User($name, $email, $birth, $password, $password_confirmation);
    }

    public function testCreateUserWithInvalidPasswordLength()
    {
        $name = 'John Doe';
        $email = 'john@gmail.com';
        $birth = '1990-04-03';
        $password = '123';
        $password_confirmation = '123';

        
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Senha precisa ser maior ou igual a 8 caracteres');
        $user = new User($name, $email, $birth, $password, $password_confirmation);
    }
}