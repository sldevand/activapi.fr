<?php

namespace Materialize\Card;

use Materialize\LinkNavbar;
use Materialize\Widget;

/**
 * Class Card
 * @package Materialize\Card
 */
class Card extends Widget
{
    /**
     * @var string $bgColor
     */
    protected $bgColor = 'teal';

    /**
     * @var string $textColor
     */
    protected $textColor = 'white-text';

    /**
     * @var string $shade
     */
    protected $shade = '';

    /**
     * @var string $title
     */
    protected $title = 'Title';

    /**
     * @var array $contents
     */
    protected $contents = [];

    /**
     * @var array $links
     */
    protected $links = [];

    /**
     * @return false|string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Card/card.phtml');
    }

    /**
     * @param LinkNavbar $link
     * @return Card
     */
    public function addLink(LinkNavbar $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function bgColor()
    {
        return $this->bgColor;
    }

    /**
     * @return string
     */
    public function textColor()
    {
        return $this->textColor;
    }

    /**
     * @return string
     */
    public function shade()
    {
        return $this->shade;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function contents()
    {
        return $this->contents;
    }

    /**
     * @return array
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * @param string $bgColor
     * @return Card
     */
    public function setBgColor($bgColor)
    {
        if (is_string($bgColor) && !empty($bgColor)) {
            $this->bgColor = $bgColor;
        }

        return $this;
    }

    /**
     * @param string $textColor
     * @return Card
     */
    public function setTextColor($textColor)
    {
        if (is_string($textColor) && !empty($textColor)) {
            $this->textColor = $textColor;
        }

        return $this;
    }

    /**
     * @param string $shade
     * @return Card
     */
    public function setShade($shade)
    {
        if (is_string($shade) && !empty($shade)) {
            $this->shade = $shade;
        }

        return $this;
    }

    /**
     * @param string $title
     * @return Card
     */
    public function setTitle($title)
    {
        if (is_string($title) && !empty($title)) {
            $this->title = $title;
        }

        return $this;
    }

    /**
     * @param array $contents
     * @return Card
     */
    public function setContents($contents)
    {
        if (!empty($contents)) {
            $this->contents = $contents;
        }

        return $this;
    }

    /**
     * @param string $content
     * @return Card
     */
    public function addContent($content)
    {
        if (!empty($content)) {
            $this->contents[] = $content;
        }

        return $this;
    }

    /**
     * @param array $links
     * @return Card
     */
    public function setLinks($links)
    {
        if (is_array($links) && !empty($links)) {
            $this->links = $links;
        }

        return $this;
    }
}
