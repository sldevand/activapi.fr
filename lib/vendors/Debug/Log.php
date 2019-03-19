<?php

namespace Debug;

/**
 * Class Log
 * @package Debug
 */
class Log
{
    /**
     * @param mixed $var
     */
    public static function d($var)
    {
        echo '<span style="background-color:black; color:white">Var Dump : </span><br>';
        echo '<pre style="background-color:white; text-color:green">';
        \var_dump($var);
        echo '</pre>';
    }

    /**
     * @param mixed $var
     */
    public static function p($var)
    {
        echo '<span style="background-color:black; color:white">Print_r : </span><br>';
        echo '<pre style="background-color:white; text-color:green">';
        \print_r($var);
        echo '</pre>';
    }
}
