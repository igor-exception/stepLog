<?php
namespace APP;

class InitController
{
    public function index(): void
    {
        require __DIR__ . '/views/init/index.php';
    }
}