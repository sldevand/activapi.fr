<?php

namespace Materialize\Sidenav;

use Materialize\Widget;

/**
 * Class Link
 * @package Materialize\Sidenav
 */
class Link extends Widget
{
    /** @var string $title */
    protected $title;

    /** @var string $link */
    protected $link;

    /** @var string $icon */
    protected $icon;

    /** @var string $align */
    protected $align;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Sidenav/templates/link.phtml');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function link()
    {
        return $this->link;
    }

    /**
     * @param string $title
     * @return Link
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $link
     * @return Link
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @param string $icon
     * @return Link
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $align
     * @return Link
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }
}
