<?php

namespace Materialize\Sidenav;

use Materialize\Widget;

/**
 * Class Sidenav
 * @package Materialize\Sidenav
 */
class Sidenav extends Widget
{
    /** @var \Materialize\Sidenav\Subheader[] */
    protected $subHeaders = [];

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Sidenav/templates/sidenav.phtml');
    }

    /**
     * @return \Materialize\Sidenav\Subheader[]
     */
    public function getSubHeaders()
    {
        return $this->subHeaders;
    }

    /**
     * @param \Materialize\Sidenav\Subheader[] $subHeaders
     * @return Sidenav
     */
    public function setSubHeaders(array $subHeaders)
    {
        $this->subHeaders = $subHeaders;

        return $this;
    }
}
