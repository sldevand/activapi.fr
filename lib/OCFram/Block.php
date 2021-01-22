<?php

namespace OCFram;

/**
 * Class Block
 * @package OCFram
 */
class Block extends ApplicationComponent
{
    /**
     * @param $fileName
     * @param mixed ...$args
     * @return false|string
     */
    public static function getTemplate($fileName, ...$args)
    {
        ob_start();
        require $fileName;
        return ob_get_clean();
    }
}
