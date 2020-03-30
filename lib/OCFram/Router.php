<?php

namespace OCFram;

use RuntimeException;

/**
 * Class Router
 * @package OCFram
 */
class Router
{
    const NO_ROUTE = 1;

    /**
     * @var array $routes
     */
    protected $routes = [];

    /**
     * @var string
     */
    protected $root;

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    /**
     * @param string $url
     * @return Route
     * @throws RuntimeException
     */
    public function getRoute($url)
    {
        foreach ($this->routes as $route) {
            if (($varsValues = $route->match($url)) !== false) {
                if ($route->hasVars()) {
                    $varsNames = $route->varsNames();
                    $listVars = [];
                    foreach ($varsValues as $key => $match) {
                        if ($key !== 0) {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    $route->setVars($listVars);
                }

                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     * @return Router
     */
    public function setRoot(string $root): Router
    {
        $this->root = $root;
        return $this;
    }
}
