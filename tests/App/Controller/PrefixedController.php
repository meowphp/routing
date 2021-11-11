<?php

namespace Meow\Routing\Test\App\Controller;

use Meow\Routing\Attributes\Prefix;
use Meow\Routing\Attributes\Route;

#[Prefix('/prefixed')]
class PrefixedController
{
    /**
     * Default route for prefix
     * /prefixed
     *
     * @return string
     */
    #[Route('/')]
    public function index(): string
    {
        return 'prefixed.index';
    }

    /**
     * route /prefixed/view/{user}
     *
     * @return string
     */
    #[Route('/view/{user}')]
    public function view(): string
    {
        return 'prefixed.view';
    }

    /**
     * route /prefixed/add
     *
     * @return string
     */
    #[Route('/add')]
    public function add(): string
    {
        return 'prefixed.add';
    }
}