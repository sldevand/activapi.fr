<?php

namespace Materialize\Sidenav;

use Materialize\Widget;

/**
 * Class Subheader
 * @package Materialize\Sidenav
 */
class Subheader extends Widget
{
    /** @var string */
    protected $title;

    /** @var \Materialize\Sidenav\Link[] */
    protected $links = [];

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Sidenav/templates/subheader.phtml');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Subheader
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return \Materialize\Sidenav\Link[]
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param \Materialize\Sidenav\Link[] $links
     * @return Subheader
     */
    public function setLinks(array $links)
    {
        $this->links = $links;

        return $this;
    }
}
