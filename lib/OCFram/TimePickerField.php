<?php

namespace OCFram;

/**
 * Class TimePickerField
 * @package OCFram
 */
class TimePickerField extends Field
{
    /**
     * @return string
     */
    public function buildWidget()
    {
        $widget = '<label>' . $this->label . '</label>';
        $widget .= '<input id="' . $this->label . '" name="' . $this->label . '" type="text" value="' . $this->value . '" class="timepicker">';

        return $widget;
    }
}
