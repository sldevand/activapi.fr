<?php

namespace Materialize\Button;

use Materialize\Button;

/**
 * Class RaisedButton
 * @package Materialize
 */
class RaisedButton extends Button
{
    /**
     * @var string
     */
    protected $extend;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB.'/vendors/Materialize/Button/raisedButton.phtml');
    }

    /**
     * @return string
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * @param string $extend
     * @return RaisedButton
     */
    public function setExtend($extend)
    {
        $this->extend = $extend;

        return $this;
    }
}
