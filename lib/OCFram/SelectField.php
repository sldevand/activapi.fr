<?php

namespace OCFram;

/**
 * Class SelectField
 * @package OCFram
 */
class SelectField extends Field
{
    /**
     * @var array $options
     */
    protected $options = [];

    /**
     * @var string $selected
     */
    protected $selected;

    /**
     * @var string $disabled
     */
    protected $disabled;

    /**
     * @return string
     */
    public function buildWidget()
    {
        $disabled = '';
        if ($this->disabled) {
            $disabled = "disabled";
        }

        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        $widget .= '<label>' . $this->label . '</label>';
        $widget .= '<select ' . $disabled . ' name="' . $this->name . '" id="' . $this->id . '">';

        foreach ($this->options as $key => $option) {
            $widget .= '<option value="' . $key . '" ';

            if ($key == $this->selected) {
                $widget .= 'selected';
            }

            $widget .= '>' . $option . '</option>';
        }

        $widget .= '</select>';

        return $widget;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        if (!is_array($options)) {
            throw new \RuntimeException('Les options sont invalides!');
        }

        $this->options = $options;
    }

    /**
     * @param string $selected
     * @return SelectField
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * @param string $disabled
     * @return SelectField
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }
}
