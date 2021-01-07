<?php

namespace OCFram;

/**
 * Class Block
 * @package OCFram
 * @author Synolia <contact@synolia.com>
 */
class Block extends ApplicationComponent
{
    /**
     * @param $fileName
     * @param mixed ...$args
     * @return false|string
     */
    public static function getBlock($fileName, ...$args)
    {
        ob_start();
        require $fileName;
        return ob_get_clean();
    }
}