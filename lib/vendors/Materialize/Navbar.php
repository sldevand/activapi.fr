<?php

namespace Materialize;

/**
 * Class Navbar
 * @package Materialize
 */
abstract class Navbar extends Widget
{
    /**
     * @var string $_title
     */
    protected $_title;

    /**
     * @var string $_logo
     */
    protected $_logo;

    /**
     * @var array $_links
     */
    protected $_links = [];

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
    public function logo()
    {
        return $this->_logo;
    }

    /**
     * @return array
     */
    public function links()
    {
        return $this->_links;
    }

    /**
     * @param $title
     * @return Navbar
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    /**
     * @param $logo
     * @return Navbar
     */
    public function setLogo($logo)
    {
        $this->_logo = $logo;

        return $this;
    }

    /**
     * @param $links
     * @return Navbar
     */
    public function setLinks($links)
    {
        $this->_links = $links;

        return $this;
    }
}
