<?php

namespace Materialize\Icon;

use Materialize\Widget;

/**
 * Class Icon
 * @package Materialize\Icon
 */
class Icon extends Widget
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $color;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Icon/templates/icon.phtml');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Icon
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Icon
     */
    public function setColor(string $color)
    {
        $this->color = $color;

        return $this;
    }
}
