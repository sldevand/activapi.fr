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
     * @var string
     */
    protected $bgColor = 'teal';
    /**
     * @var string
     */
    protected $textColor = 'white-text';
    /**
     * @var string
     */
    protected $shade = '';
    /**
     * @var string
     */
    protected $title = 'Title';
    /**
     * @var array
     */
    protected $contents = [];
    /**
     * @var array
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
     */
    public function addLink(LinkNavbar $link)
    {
        $this->links[] = $link;
    }

    //GETTERS

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
     * @return mixed
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

    //SETTERS

    /**
     * @param $bgColor
     */
    public function setBgColor($bgColor)
    {
        if (is_string($bgColor) && !empty($bgColor)) {
            $this->bgColor = $bgColor;
        }
    }

    /**
     * @param $textColor
     */
    public function setTextColor($textColor)
    {
        if (is_string($textColor) && !empty($textColor)) {
            $this->textColor = $textColor;
        }
    }

    /**
     * @param $shade
     */
    public function setShade($shade)
    {
        if (is_string($shade) && !empty($shade)) {
            $this->shade = $shade;
        }
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        if (is_string($title) && !empty($title)) {
            $this->title = $title;
        }
    }

    /**
     * @param array $contents
     */
    public function setContents($contents)
    {
        if (!empty($contents)) {
            $this->contents = $contents;
        }
    }

    /**
     * @param $content
     */
    public function addContent($content)
    {
        if (!empty($content)) {
            $this->contents[] = $content;
        }
    }

    /**
     * @param $links
     */
    public function setLinks($links)
    {
        if (is_array($links) && !empty($links)) {
            $this->links = $links;
        }
    }


}