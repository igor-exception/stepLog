<?php
namespace APP;
use APP\Controller;
class InitController extends Controller
{
    public function index(): void
    {
        $this->render('init/index');
    }
}