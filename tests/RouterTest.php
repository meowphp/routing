<?php

namespace Meow\Routing\Test;

use Meow\Routing\Attributes\Route;
use Meow\Routing\Router;
use Meow\Routing\Test\App\Controller\MainController;
use Meow\Routing\Test\App\Controller\UsersController;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected array $controllers = [
        MainController::class,
        UsersController::class
    ];

    public function testRouteWithParameter()
    {
        $router = Router::getRouter($this->controllers);

        $route = $router->matchFromUrl('/view/1');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('view', $route->getAction());
        $this->assertArrayHasKey('id', $route->getParameters());
        $this->assertEquals('1', $route->getParameters()['id']);
        $this->assertEquals(MainController::class, $route->getController());
        $this->assertTrue($route->hasParameters());
    }

    public function testDefaultRoute()
    {
        $router = Router::getRouter($this->controllers);

        $route = $router->matchFromUrl('/');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('index', $route->getAction());
        $this->assertEquals(MainController::class, $route->getController());
        $this->assertFalse($route->hasParameters());
    }

    public function testRouteWithMultipleParameters()
    {
        $router = Router::getRouter($this->controllers);

        $route = $router->matchFromUrl('/users/john/doe/view');

        $this->assertInstanceOf(Route::class, $route);
        $this->assertEquals('view', $route->getAction());
        $this->assertEquals(UsersController::class, $route->getController());
        $this->assertTrue($route->hasParameters());

        $this->assertArrayHasKey('name', $route->getParameters());
        $this->assertEquals('john', $route->getParameters()['name']);

        $this->assertArrayHasKey('surname', $route->getParameters());
        $this->assertEquals('doe', $route->getParameters()['surname']);
    }
}