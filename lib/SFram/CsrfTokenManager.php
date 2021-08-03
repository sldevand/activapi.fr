<?php

namespace SFram;

use Exception;

/**
 * Class CsrfTokenManager
 * @package SFram
 */
class CsrfTokenManager
{
    /**
     * @throws \Exception
     */
    public static function generate()
    {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    /**
     * @return mixed
     */
    public static function get(): string
    {
        return $_SESSION['token'];
    }

    /**
     * @param string $token
     * @throws \Exception
     */
    public static function verify(string $token)
    {
        if (!hash_equals($_SESSION['token'], $token)) {
            throw new Exception('CSRF token is invalid');
        }
    }
}