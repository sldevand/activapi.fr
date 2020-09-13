<?php

namespace Materialize\Icon;

/**
 * Class YesNoIconifier
 * @package Materialize\Icon
 */
class YesNoIconifier
{
    /**
     * @param string|int|bool $state
     * @return string
     */
    public function iconifyResult($state)
    {
        if ($state) {
            $icon = "check";
            $color = "teal-text";
        } else {
            $icon = "cancel";
            $color = "secondaryTextColor";
        }

        return '<i class="material-icons ' . $color . ' ">' . $icon . '</i>';
    }
}
