<?php

namespace SFram;

/**
 * Class CsrfTokenManager
 * @package SFram
 */
class CsrfTokenManager
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generate(): string
    {
        if (empty($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['token'];
    }


    /**
     * @param string $token
     * @return bool
     */
    public function verify(string $token): bool
    {
        return hash_equals($_SESSION['token'], $token);
    }

    public function revoke()
    {
        unset($_SESSION['token']);
    }
}