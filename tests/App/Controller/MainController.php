<?php

namespace Meow\Routing\Test\App\Controller;

use Meow\Routing\Attributes\Route;

class MainController
{
    #[Route('/')]
    public function index(): string
    {
        return 'main.index';
    }

    #[Route('/view/{id}')]
    public function view(): string
    {
        return 'main.view';
    }
}