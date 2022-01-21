<?php

namespace Materialize\Link;

use Materialize\Widget;

/**
 * Class Link
 * @package Materialize\Link
 */
class Link extends Widget
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
     * @var string $_iconColor
     */
    protected $_iconColor;

    /**
     * @var string $_titleColor
     */
    protected $_titleColor;

    /**
     * @var string $_align
     */
    protected $_align;


    /**
     * Link constructor.
     * @param string $title
     * @param string $link
     * @param string $icon
     * @param string $iconColor
     * @param string $titleColor
     */
    public function __construct($title, $link, $icon = '', $iconColor = '', $titleColor = '')
    {
        parent::__construct([]);
        $this->setTitle($title);
        $this->setLink($link);
        $this->setIcon($icon);
        $this->setIconColor($iconColor);
        $this->setTitleColor($titleColor);
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Link/templates/link.phtml');
    }

    /**
     * @return string
     */
    public function getHtmlForTable()
    {
        $iconHtml = '<i class="material-icons ' . $this->iconColor() . ' left">' . $this->icon() . '</i>';

        return '<a href="' . $this->_link . '" style="margin:10px;" class="center">' . $iconHtml . $this->title() . '</a>';
    }

    /**
     * @return string
     */
    public function iconColor()
    {
        return $this->_iconColor;
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
    public function titleColor()
    {
        return $this->_titleColor;
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
     * @return Link
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @param string $link
     * @return Link
     */
    public function setLink($link)
    {
        $this->_link = $link;
        return $this;
    }

    /**
     * @param string $icon
     * @return Link
     */
    public function setIcon($icon)
    {
        $this->_icon = $icon;
        return $this;
    }

    /**
     * @param string $iconColor
     * @return Link
     */
    public function setIconColor($iconColor)
    {
        $this->_iconColor = $iconColor;
        return $this;
    }

    /**
     * @param string $titleColor
     * @return Link
     */
    public function setTitleColor($titleColor)
    {
        $this->_titleColor = $titleColor;
        return $this;
    }

    /**
     * @param string $align
     * @return Link
     */
    public function setAlign($align)
    {
        $this->_align = $align;
        return $this;
    }

    /**
     * @return string
     */
    public function align(): string
    {
        return $this->_align ?? '';
    }
}
