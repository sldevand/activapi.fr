<?php

namespace OCFram;

/**
 * Class HTTPRequest
 * @package OCFram
 */
class HTTPRequest extends ApplicationComponent
{
    /**
     * @param string $key
     * @return null
     */
    public function cookieData($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function cookieExists($key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * @param string $key
     * @return null
     */
    public function getData($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function getExists($key)
    {
        return isset($_GET[$key]);
    }

    /**
     * @return mixed
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param string $key
     * @return null
     */
    public function postData($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function postExists($key)
    {
        return isset($_POST[$key]);
    }

    /**
     * @return bool
     */
    public function postsExist()
    {
        return !empty($_POST);
    }

    /**
     * @return mixed
     */
    public function getJsonPost()
    {
        return $_POST = json_decode(file_get_contents('php://input'), true);
    }

    /**
     * @return mixed
     */
    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
