<?php
declare(strict_types=1);

namespace Meow\Routing\Attributes;

use Meow\Routing\Exceptions\WrongRouteNameFormatException;
use Meow\Routing\Tools\Text;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Route
{
    /** @var string $routePrefix */
    public string $routePrefix = '/';

    /** @var string $routeName Name of route */
    protected string $routeName;

    /** @var string $controller Name of Controller */
    protected string $controller;

    /** @var string $action Name of called action in controller */
    protected string $action;

    /** @var array<string> $parameters Option parameters for routes  */
    protected array $parameters;

    public function __construct(string $routeName)
    {
        if (!Text::startWith($routeName, '\/') || Text::endsWith($routeName, '\/')) {
            throw new WrongRouteNameFormatException('Route name must start with slash and must not end with slash');
        }

        $this->routeName = $routeName;
    }

    /**
     * @return string Controller name
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }


    /**
     * @return string returns route name
     */
    public function getRouteName(): string
    {
        // route /prefix/routeName
        if ($this->routePrefix != '/' && $this->routeName != '/') {
            return $this->routePrefix . $this->routeName;
        }

        // route /prefix
        if ($this->routePrefix != '/' && $this->routeName = '/') {
            return $this->routePrefix;
        }

        // route /routeName
        return $this->routeName;
    }

    /**
     * Try to match parameters
     *
     * @param string $routeName
     * @return bool
     */
    public function match(string $routeName): bool
    {
        $regex = $this->getRouteName();
        foreach ($this->getParametersNames() as $routeParam) {
            $routeParamName = trim($routeParam, '{\}');
            $regex = str_replace($routeParam, '(?P<' . $routeParamName . '>[^/]++)', $regex);
        }

        if (preg_match('#^' . $regex . '$#sD', self::trimPath($routeName), $matches)) {
            $values = array_filter($matches, static function($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);

            foreach ($values as $key => $value) {
                $this->parameters[$key] = $value;
            }

            return true;
        }
        return false;
    }

    /**
     * @return array<string> Array of parameters names
     */
    public function getParametersNames() : array
    {
        preg_match_all('/{[^}]*}/', $this->getRouteName(), $matches);
        return reset($matches) ?? [];
    }

    public function hasParameters() : bool
    {
        return $this->getParametersNames() !== [];
    }

    /**
     * @return string[] Array of parameters values
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    public static function trimPath(string $path) : string
    {
        return '/' . rtrim(ltrim(trim($path), '/'), '/');
    }

    /**
     * @param string $routePrefix
     */
    public function setRoutePrefix(string $routePrefix): void
    {
        $this->routePrefix = $routePrefix;
    }
}