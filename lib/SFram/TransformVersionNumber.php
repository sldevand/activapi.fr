<?php

namespace SFram;

trait TransformVersionNumber
{

    /**
     * @param $versionNumber
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
