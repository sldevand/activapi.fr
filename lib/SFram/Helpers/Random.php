<?php

namespace SFram\Helpers;

/**
 * Class Random
 * @package SFram\Helpers
 */
class Random
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function createRandomToken()
    {
        if (!isset($length) || intval($length) <= 8) {
            $length = 32;
        }

        return bin2hex(random_bytes($length));
    }
}
