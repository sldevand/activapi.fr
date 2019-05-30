<?php

namespace Materialize\Button;

use Materialize\Button;

/**
 * Class FlatButton
 * @package Materialize
 */
class FlatButton extends Button
{
    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Button/flatButton.phtml');
    }
}
