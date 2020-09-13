<?php

namespace Materialize\Button;

use Materialize\Button;

/**
 * Class SwitchButton
 * @package Materialize\Button
 */
class SwitchButton extends Button
{
    /** @var string */
    protected $checked;

    /** @var string */
    protected $name;

    /** @var string */
    protected $value;

    /** @var string */
    protected $leftText = 'Off';

    /** @var string */
    protected $rightText = 'On';


    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getBlock(LIB . '/vendors/Materialize/Button/switchButton.phtml');
    }

    /**
     * @param string $checked
     * @return \Materialize\Button\SwitchButton
     */
    public function setChecked(string $checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * @return string
     */
    public function checked()
    {
        return $this->checked;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SwitchButton
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return SwitchButton
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function leftText()
    {
        return $this->leftText;
    }

    /**
     * @param string $leftText
     * @return \Materialize\Button\SwitchButton
     */
    public function setLeftText(string $leftText)
    {
        $this->leftText = $leftText;

        return $this;
    }

    /**
     * @return string
     */
    public function rightText()
    {
        return $this->rightText;
    }

    /**
     * @param string $rightText
     * @return \Materialize\Button\SwitchButton
     */
    public function setRightText(string $rightText)
    {
        $this->rightText = $rightText;

        return $this;
    }
}
