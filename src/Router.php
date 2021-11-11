<?php
declare(strict_types=1);

namespace Meow\Routing;

use Meow\Routing\Attributes\Prefix;
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
        $controllerPrefixName = null;

        $router = new self();

        foreach ($controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);

            /** @var array<\ReflectionAttribute<Prefix>> $controllerPrefixAttributes */
            $controllerPrefixAttributes = $reflectionClass->getAttributes(Prefix::class);

            if (!empty($controllerPrefixAttributes)) {
                /** @var Prefix $controllerPrefix */
                $controllerPrefix = $controllerPrefixAttributes[0]->newInstance();
                $controllerPrefixName = $controllerPrefix->getPrefixName();
            }

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

                        // add prefix to route
                        if (!is_null($controllerPrefixName)) {
                            $actionRoute->setRoutePrefix($controllerPrefixName);
                        }

                        $router->addRoute($actionRoute);
                    }
                }
            }
        }

        return $router;
    }
}