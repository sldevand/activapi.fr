<?php

namespace OCFram;

use Exception;

/**
 * Class HTTPRequest
 * @package OCFram
 */
class HTTPRequest extends ApplicationComponent
{
    const POST = 'POST';
    const PUT = 'PUT';
    const GET = 'GET';
    const DELETE = 'DELETE';

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
     * @param $key
     * @return mixed
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
     * @throws Exception
     */
    public function getJsonPost()
    {
        if (empty($jsonBody = file_get_contents('php://input'))) {
            throw new Exception('No JSON body sent from client');
        }

        if (!empty($jsonPost = json_decode($jsonBody, true))) {
            $_POST = $jsonPost;
            return $_POST;
        }

        /** get form params */
        parse_str(file_get_contents("php://input"), $params);
        $_POST = $params;

        return $_POST;
    }

    /**
     * @return mixed
     */
    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}
