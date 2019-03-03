<?php

namespace OCFram;

/**
 * Class HTTPResponse
 * @package OCFram
 */
class HTTPResponse extends ApplicationComponent
{
    /**
     * @var Page $page
     */
    protected $page;

    /**
     * @param string $location
     */
    public function redirect($location)
    {
        header('Location: ' . $location);
        exit;
    }

    public function redirect404()
    {
        $this->page = new Page($this->app);
        $this->page->setContentFile(__DIR__ . '/../../Errors/404.html');
        $this->addHeader('HTTP/1.0 404 Not Found');
        $this->send();
    }

    /**
     * @param $header
     */
    public function addHeader($header)
    {
        header($header);
    }

    public function send()
    {
        exit($this->page->getGeneratedPage());
    }

    public function sendJSON()
    {
        header('Content-Type: application/json');
        exit($this->page->getGeneratedJSON());
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @param $name
     * @param string $value
     * @param int $expire
     * @param null $path
     * @param null $domain
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function setCookie(
        $name,
        $value = '',
        $expire = 0,
        $path = null,
        $domain = null,
        $secure = false,
        $httpOnly = true
    ) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}
