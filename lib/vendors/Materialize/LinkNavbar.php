<?php

namespace Materialize;
/**
 * Class LinkNavbar
 * @package Materialize
 */
class LinkNavbar extends Widget
{

    /**
     * @var string $_title
     */
    protected $_title;

    /**
     * @var string $_link
     */
    protected $_link;

    /**
     * @var string $_icon
     */
    protected $_icon;

    /**
     * @var string $_align
     */
    protected $_align;

    /**
     * LinkNavbar constructor.
     * @param string $title
     * @param string $link
     * @param string $icon
     */
    public function __construct($title, $link, $icon = '')
    {
        parent::__construct([]);
        $this->setTitle($title);
        $this->setLink($link);
        $this->setIcon($icon);
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $iconHtml = '<i class="material-icons left">' . $this->icon() . '</i>';

        return '<li><a href="' . $this->_link . '">' . $iconHtml . $this->_title . '</a></li>';
    }

    /**
     * @return string
     */
    public function getIconHtml()
    {
        return '<i class="material-icons">' . $this->icon() . '</i>';
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->_icon;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->_title;
    }

    /**
     * @return string
     */
    public function link()
    {
        return $this->_link;
    }

    /**
     * @param string $title
     * @return LinkNavbar
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @param string $link
     * @return LinkNavbar
     */
    public function setLink($link)
    {
        $this->_link = $link;
        return $this;
    }

    /**
     * @param string $icon
     * @return LinkNavbar
     */
    public function setIcon($icon)
    {
        $this->_icon = $icon;
        return $this;
    }

    /**
     * @param string $align
     * @return LinkNavbar
     */
    public function setAlign($align)
    {
        $this->_align = $align;
        return $this;
    }
}
