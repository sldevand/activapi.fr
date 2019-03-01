<?php

namespace OCFram;

/**
 * Class NumberField
 * @package OCFram
 */
class NumberField extends Field
{
    /**
     * @var float $min
     */
    protected $min;
    /**
     * @var float $max
     */
    protected $max;
    /**
     * @var float $step
     */
    protected $step;

    /**
     * @return mixed|string
     */
    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        if (!empty($this->label)) {
            $widget .= '<label for="'.$this->name.'">' . $this->label . '</label>';
        }

        $widget .= '<input type="number" name="' . $this->name . '" id="'.$this->name.'"';

        if (isset($this->value)) {
            $widget .= ' value="' . htmlspecialchars($this->value) . '"';
        }

        if (isset($this->min)) {
            $widget .= ' min="' . $this->min . '"';
        }

        if (isset($this->max)) {
            $widget .= ' max="' . $this->max . '"';
        }

        if (isset($this->step)) {
            $widget .= ' step="' . $this->step . '"';
        }

        return $widget .= ' />';
    }

    /**
     * @param $min
     * @return NumberField
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param $max
     * @return NumberField
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param $step
     * @return NumberField
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }
}
