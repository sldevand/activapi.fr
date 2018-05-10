<?php
namespace OCFram;

class NumberField extends Field
{
  protected $min;
  protected $max;
  protected $step;
  
  public function buildWidget()
  {
    $widget = '';
    
    if (!empty($this->errorMessage))
    {
      $widget .= $this->errorMessage.'<br />';
    }
    
    $widget .= '<label>'.$this->label.'</label><input type="number" name="'.$this->name.'"';
    
    if (isset($this->value))
    {
      $widget .= ' value="'.htmlspecialchars($this->value).'"';
    }

    if (isset($this->min))
    {
      $widget .= ' min="'.$this->min.'"';
    }

    if (isset($this->max))
    {
      $widget .= ' max="'.$this->max.'"';
    }
    
    if (isset($this->step))
    {
      $widget .= ' step="'.$this->step.'"';
    }
    
    return $widget .= ' />';
  }

  public function setMin($min){    
    $this->min = $min;    
  }
  
  public function setMax($max){    
    $this->max = $max;    
  }

  public function setStep($step){    
    $this->step = $step;
  }
}