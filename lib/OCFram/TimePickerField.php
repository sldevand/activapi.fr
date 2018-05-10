<?php
namespace OCFram;

class TimePickerField extends Field
{

  
  public function buildWidget()
  {
    $widget = '';
  
    $widget .= '<label>'.$this->label.'</label>';
    $widget .='<input id="'.$this->label.'" name="'.$this->label.'" type="text" value="'.$this->value.'" class="timepicker">'; 
  
    return $widget;
  }
  
  
}