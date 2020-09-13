<?php

namespace OCFram;

/**
 * Class Route
 * @package OCFram
 */
class Route
{
    const SCOPE_PRIVATE = 'private';
    const SCOPE_PUBLIC  = 'public';

    /**
     * @var string $action
     */
    protected $action;

    /**
     * @var string $module
     */
    protected $module;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var array $varsNames
     */
    protected $varsNames;

    /**
     * @var array $vars
     */
    protected $vars = [];

    /**
     * @var string
     */
    protected $scope;

    /**
     * Route constructor.
     * @param $url
     * @param $module
     * @param $action
     * @param array $varsNames
     * @param string $scope
     */
    public function __construct($url, $module, $action, array $varsNames, string $scope = self::SCOPE_PRIVATE)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setVarsNames($varsNames);
        $this->setScope($scope);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->url = $url;
        }
    }

    /**
     * @param string $module
     */
    public function setModule($module)
    {
        if (is_string($module)) {
            $this->module = $module;
        }
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        if (is_string($action)) {
            $this->action = $action;
        }
    }

    /**
     * @param array $varsNames
     */
    public function setVarsNames(array $varsNames)
    {
        $this->varsNames = $varsNames;
    }

    /**
     * @return bool
     */
    public function hasVars()
    {
        return !empty($this->varsNames);
    }

    /**
     * @param string $url
     * @return bool
     */
    public function match($url)
    {
        if (!preg_match('`^' . $this->url . '$`', $url, $matches)) {
            return false;
        }

        return $matches;
    }

    /**
     * @param array $vars
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function module()
    {
        return $this->module;
    }

    /**
     * @return array
     */
    public function vars()
    {
        return $this->vars;
    }

    /**
     * @return array
     */
    public function varsNames()
    {
        return $this->varsNames;
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     * @return \OCFram\Route
     */
    public function setScope(?string $scope)
    {
        $this->scope = $scope;

        return $this;
    }
}
