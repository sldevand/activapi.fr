<?php

namespace Materialize;

/**
 * Class Button
 * @package Materialize
 */
abstract class Button extends Widget
{
    /**
     * @var string
     */
    protected $title = null;
    /**
     * @var string
     */
    protected $icon = null;
    /**
     * @var string
     */
    protected $align = 'left';
    /**
     * @var string
     */
    protected $href = '';
    /**
     * @var string
     */
    protected $type = '';
    /**
     * @var string
     */
    protected $color = '';

    /**
     * @return null
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * @return null
     */
    public function icon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function align()
    {
        return $this->align;
    }

    /**
     * @return string
     */
    public function href()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function color()
    {
        return $this->color;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param $align
     */
    public function setAlign($align)
    {

        if ($align == 'right') {
            $this->align = $align;
        } else {
            $this->align = 'left';
        }
    }

    /**
     * @param $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return string
     */
    public function getIconHtml()
    {
        $icon = '';
        if (!is_null($this->icon())) {
            $icon = '<i class="material-icons ' . $this->align() . ' ' . $this->color() . '">' . $this->icon() . '</i>';
        }

        return $icon;
    }
}
