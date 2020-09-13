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
     * @var string
     */
    protected $size = '';


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

    /**
     * @return string
     */
    public function size(): string
    {
        return $this->size;
    }

    /**
     * @param string $title
     * @return Button
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $icon
     * @return Button
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string $align
     * @return Button
     */
    public function setAlign($align)
    {
        if ($align === 'right') {
            $this->align = $align;
        } else {
            $this->align = 'left';
        }

        return $this;
    }

    /**
     * @param string $href
     * @return Button
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * @param string $type
     * @return Button
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $color
     * @return Button
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string $size
     * @return Button
     */
    public function setSize(string $size)
    {
        $this->size = $size;

        return $this;
    }
}
