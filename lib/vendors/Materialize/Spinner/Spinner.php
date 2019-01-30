<?php

namespace Materialize\Spinner;

use Materialize\Widget;

/**
 * Class Spinner
 * @package Materialize
 */
class Spinner extends Widget
{

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Spinner/spinner.phtml');
    }
}