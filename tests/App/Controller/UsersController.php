<?php

namespace Meow\Routing\Test\App\Controller;

use Meow\Routing\Attributes\Route;

class UsersController
{
    #[Route('/users/{name}/{surname}/view')]
    public function view(): string
    {
        return 'users.view';
    }
}