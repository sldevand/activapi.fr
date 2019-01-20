<?php

namespace Materialize;

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
        return '<a id="' . $this->id() . '" class="waves-effect waves-light btn '.$this->extend.'">' . $this->getIconHtml() . $this->title() . '</a>';
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
