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
            $widget .= '<label for="' . $this->id . '">' . $this->label . '</label>';
        }

        $widget .= '<input type="number" name="' . $this->name . '" id="' . $this->id . '"';

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
     * @param float $min
     * @return NumberField
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * @param float $max
     * @return NumberField
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * @param float $step
     * @return NumberField
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }
}
