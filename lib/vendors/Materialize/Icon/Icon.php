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
}
