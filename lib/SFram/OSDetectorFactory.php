<?php

namespace SFram;

use OSDetector\Detector;

/**
 * Class OSDetectorFactory
 * @package SFram
 */
class OSDetectorFactory
{
    /**
     * @var \OSDetector\Detector $detector
     */
    public static $detector;

    /**
     * @return bool
     */
    public static function begin()
    {
        self::$detector = new Detector();

        return true;
    }

    /**
     * @return string
     */
    public static function getKernelName()
    {
        return self::$detector->getKernelName();
    }

    /**
     * @return bool
     */
    public static function isUnixLike()
    {
        return self::$detector->isUnixLike();
    }

    /**
     * @return int
     */
    public static function isWindowsLike()
    {
        return self::$detector->isWindowsLike();
    }

    /**
     * @return string
     */
    public static function getApiAddressKey()
    {
        if (OSDetectorFactory::isWindowsLike()) {
            return 'apiWinBaseAddress';
        }

        return 'apiLinBaseAddress';
    }

    /**
     * @return string
     */
    public static function getPdoAddressKey()
    {
        if (OSDetectorFactory::isWindowsLike()) {
            return 'pdoWinAddress';
        }

        return 'pdoLinAddress';
    }
}
