<?php

namespace SFram;

/**
 * Trait TransformVersionNumber
 * @package SFram
 */
trait TransformVersionNumber
{
    /**
     * @param string $versionNumber
     * @return int
     */
    public function getComparableVersionNumber($versionNumber)
    {
        $arr = explode('.', $versionNumber);
        if (!empty($arr)) {
            return implode('', $arr);
        }

        return 0;
    }
}
