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
     */
    public function getController()
    {
        $xml = new \DOMDocument();
        $xml->load(__DIR__ . '/../../App/' . $this->name . '/Config/routes.xml');

        $routes = $xml->getElementsByTagName('route');
        $root = $xml->getElementsByTagName('root')->item(0);
        $rootUrl = $root->getAttribute('url');

        $rootUrl = str_replace('\/', '/', $rootUrl);
        $this->router->setRoot($rootUrl);

        foreach ($routes as $route) {
            $vars = [];

            if ($route->hasAttribute('vars')) {
                $vars = explode(',', $route->getAttribute('vars'));
            }

            $fullUrl = $rootUrl . $route->getAttribute('url');

            $this->router->addRoute(new Route($fullUrl, $route->getAttribute('module'), $route->getAttribute('action'), $vars));
        }

        try {
            $matchedRoute = $this->router->getRoute($this->httpRequest->requestURI());
        } catch (\RuntimeException $e) {
            if ($e->getCode() == Router::NO_ROUTE) {
                $this->httpResponse->redirect404();
            }
        }

        $_GET = array_merge($_GET, $matchedRoute->vars());
        $controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\' . $matchedRoute->module() . 'Controller';

        return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());
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
}
