<?php

namespace OCFram;

/**
 * Class Application
 * @package OCFram
 */
abstract class Application
{
    /**
     * @var HTTPRequest $httpRequest
     */
    protected $httpRequest;

    /**
     * @var HTTPResponse $httpResponse
     */
    protected $httpResponse;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Router $router
     */
    protected $router;

    /**
     * @var string
     */
    protected $root;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);
        $this->user = new User($this);
        $this->config = new Config($this);
        $this->router = new Router();

        $this->name = '';
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getController()
    {
        $this->root = $this->name === 'Frontend'
            ? ROOT
            : ROOT_API;

        $this->addRoutesToRouter();
        $matchedRoute = $this->matchRoute();
        $this->checkRoutePermission($matchedRoute);

        $_GET = array_merge($_GET, $matchedRoute->vars());
        $controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\' . $matchedRoute->module() . 'Controller';

        return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
    }

    /**
     *
     */
    protected function addRoutesToRouter()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__ . '/../../App/' . $this->name . '/Config/routes.xml');

        $routesXml = $xml->getElementsByTagName('route');

        /** @var \DOMElement $routeXml */
        foreach ($routesXml as $routeXml) {
            $vars = [];

            if ($routeXml->hasAttribute('vars')) {
                $vars = explode(',', $routeXml->getAttribute('vars'));
            }

            $fullUrl = $this->root . $routeXml->getAttribute('url');

            $scope = Route::SCOPE_PRIVATE;
            if ($routeXml->hasAttribute('scope')) {
                $scope = $routeXml->getAttribute('scope');
            }

            $this->router->addRoute(
                new Route($fullUrl, $routeXml->getAttribute('module'), $routeXml->getAttribute('action'), $vars, $scope)
            );
        }
    }

    /**
     * @return Route | null
     */
    protected function matchRoute()
    {
        try {
            return $this->router->getRoute($this->httpRequest->requestURI());
        } catch (\RuntimeException $e) {
            $this->httpResponse->redirect404();
            return null;
        }
    }

    /**
     * @param \OCFram\Route $route
     */
    protected function checkRoutePermission(Route $route)
    {
        if ($route->getScope() === Route::SCOPE_PRIVATE && !$this->user()->isAuthenticated()) {
            $this->user()->setFlash('You cannot access this page because you are not logged in!');
            $this->httpResponse->redirect($this->root . '/login');
        }
    }

    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * @return HTTPRequest
     */
    public function httpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return HTTPResponse
     */
    public function httpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Config
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
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
     */
    public function setRoot(string $root): void
    {
        $this->root = $root;
    }
}
