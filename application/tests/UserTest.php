<?php
use PHPUnit\Framework\TestCase;
use App\User;

class UserTest extends TestCase
{
    public function testEquals()
    {
        $a = 1;
        $b = 1;
        $this->assertEquals($a, $b);
    }
}