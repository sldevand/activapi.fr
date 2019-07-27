<?php

namespace Materialize\Button;

use Materialize\Button;

/**
 * Class SwitchButton
 * @package Materialize\Button
 */
class SwitchButton extends Button
{
    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Button/switchButton.phtml');
    }
}
