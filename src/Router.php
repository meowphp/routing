<?php
declare(strict_types=1);

namespace Meow\Routing;

use Meow\Routing\Attributes\Route;
use Meow\Routing\Exceptions\NotFoundRouteException;

class Router
{
    /** @var array<Route> $routes  */
    protected array $routes = [];

    /**
     * Add route to the router
     *
     * @param Route $route
     */
    public function addRoute(Route $route): void
    {
        array_push($this->routes, $route);
    }

    /**
     * @param string $url
     * @return Route Active route
     * @throws NotFoundRouteException
     */
    public function matchFromUrl(string $url): Route
    {
        foreach ($this->routes as $route) {
            if ($route->match($url) == false) {
                continue;
            }

            return $route;
        }

        throw new NotFoundRouteException('Route not found');
    }

    /**
     * @param array<class-string> $controllers
     * @return Router
     */
    public static function getRouter(array $controllers): Router
    {
        $router = new self();

        foreach ($controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);

            /** @var array<\ReflectionMethod> $controllerActions */
            $controllerActions = $reflectionClass->getMethods();

            if (!empty($controllerActions)){
                foreach ($controllerActions as $controllerAction) {
                    /** @var array<\ReflectionAttribute<Route>> $actionRouteAttributes */
                    $actionRouteAttributes = $controllerAction->getAttributes(Route::class);

                    if (!empty($actionRouteAttributes)) {
                        /** @var Route $actionRoute */
                        $actionRoute = $actionRouteAttributes[0]->newInstance();

                        $actionRoute->setController($controller);
                        $actionRoute->setAction($controllerAction->getName());

                        $router->addRoute($actionRoute);
                    }
                }
            }
        }

        return $router;
    }
}