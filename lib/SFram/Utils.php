<?php

namespace SFram;

/**
 * Class Utils
 * @package SFram
 */
class Utils
{
    /**
     * @param mixed $obj
     * @return array
     */
    public static function objToArray($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    /**
     * @param mixed $obj
     * @return mixed
     */
    public static function deepCopy($obj)
    {
        return unserialize(serialize($obj));
    }
}
