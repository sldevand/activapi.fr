<?php

namespace Materialize\Pagination;

use Materialize\Widget;

/**
 * Class Pagination
 * @package Materialize\Pagination
 */
class Pagination extends Widget
{
    /** @var array */
    protected $pages = [];

    /** @var string */
    protected $hrefPrev;

    /** @var string */
    protected $hrefNext;

    /** @var string */
    protected $classPrev;

    /** @var string */
    protected $classNext;

    /**
     * @return false|string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Pagination/pagination.phtml');
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @param array $pages
     * @return Pagination
     */
    public function setPages(array $pages): Pagination
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * @return string
     */
    public function getHrefPrev(): string
    {
        return $this->hrefPrev;
    }

    /**
     * @param string $hrefPrev
     * @return Pagination
     */
    public function setHrefPrev(string $hrefPrev): Pagination
    {
        $this->hrefPrev = $hrefPrev;

        return $this;
    }

    /**
     * @return string
     */
    public function getHrefNext(): string
    {
        return $this->hrefNext;
    }

    /**
     * @param string $hrefNext
     * @return Pagination
     */
    public function setHrefNext(string $hrefNext): Pagination
    {
        $this->hrefNext = $hrefNext;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassPrev(): string
    {
        return $this->classPrev;
    }

    /**
     * @param string $classPrev
     * @return Pagination
     */
    public function setClassPrev(string $classPrev): Pagination
    {
        $this->classPrev = $classPrev;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassNext(): string
    {
        return $this->classNext;
    }

    /**
     * @param string $classNext
     * @return Pagination
     */
    public function setClassNext(string $classNext): Pagination
    {
        $this->classNext = $classNext;

        return $this;
    }
}
