<?php
namespace APP;

abstract class Controller
{
    public function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/views/$view.php";
    }

    public function redirect(string $url): void
    {
        header("Location: $url");
        return;
    }
}