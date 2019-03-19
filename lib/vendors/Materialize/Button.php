<?php

namespace Materialize;

/**
 * Class Button
 * @package Materialize
 */
abstract class Button extends Widget
{
    /**
     * @var string $title
     */
    protected $title = null;

    /**
     * @var string $icon
     */
    protected $icon = null;

    /**
     * @var string $align
     */
    protected $align = 'left';

    /**
     * @var string $href
     */
    protected $href = '';

    /**
     * @var string $type
     */
    protected $type = '';

    /**
     * @var string $color
     */
    protected $color = '';

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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param string $align
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
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $color
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
    public function align()
    {
        return $this->align;
    }

    /**
     * @return string
     */
    public function color()
    {
        return $this->color;
    }
}
