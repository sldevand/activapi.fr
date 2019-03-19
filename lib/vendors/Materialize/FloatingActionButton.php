<?php

namespace Materialize;

/**
 * Class FloatingActionButton
 * @package Materialize
 */
class FloatingActionButton extends Button
{
    /**
     * @var bool
     */
    protected $_fixed = false;

    /**
     * @return string
     */
    public function getHtml()
    {
        $fixedHtml = '';

        if ($this->fixed()) {
            $fixedHtml = '<div class="fixed-action-btn">';
        }

        $returnHtml = $fixedHtml . '<a href="' . $this->href() . '" id="' . $this->id() . '" class="btn-floating btn-large waves-effect waves-light btn secondaryColor">' . $this->getIconHtml() . '</a>';

        if ($this->fixed()) {
            $returnHtml .= '</div>';
        }

        return $returnHtml;
    }

    /**
     * @return bool
     */
    public function fixed()
    {
        return $this->_fixed;
    }

    /**
     * @param bool $fixed
     * @return FloatingActionButton
     */
    public function setFixed($fixed)
    {
        if (is_bool($fixed)) {
            $this->_fixed = $fixed;
        }

        return $this;
    }
}
