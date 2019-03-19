<?php

namespace OCFram;

/**
 * Class User
 * @package OCFram
 */
class User
{
    /**
     * @var Application $app
     */
    protected $app;

    /**
     * User constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        if (empty($_SESSION)) {
            session_start();
        }
        $this->app = $app;
    }

    /**
     * @param string $attr
     * @return null | string
     */
    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    /**
     * @return string
     */
    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    /**
     * @return bool
     */
    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    /**
     * @param string $attr
     * @param string $value
     */
    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    /**
     * @param bool $authenticated
     */
    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            throw new \InvalidArgumentException('La valeur spécifiée à la méthode User::setAuthenticated() doit être un boolean');
        }

        $_SESSION['auth'] = $authenticated;
    }

    /**
     * @param string $value
     */
    public function setFlash($value)
    {
        $_SESSION['flash'] = $value;
    }
}
