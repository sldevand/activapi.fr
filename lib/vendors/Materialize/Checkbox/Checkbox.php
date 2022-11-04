<?php

namespace Materialize\Checkbox;

use Materialize\Widget;

class Checkbox extends Widget
{
    protected bool $checked = false;
    protected string $label = '';
    protected string $name = '';

    /**
     * @return false|string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Checkbox/checkbox.phtml');
    }

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     * @return Checkbox
     */
    public function setChecked(bool $checked): Checkbox
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Checkbox
     */
    public function setLabel(string $label): Checkbox
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Checkbox
     */
    public function setName(string $name): Checkbox
    {
        $this->name = $name;
        return $this;
    }
}