<?php
namespace OCFram;

class SelectField extends Field
{
  protected $options=[];
  protected $selected;
  protected $disabled;
  
  public function buildWidget()
  { $disabled='';
    if($this->disabled) $disabled="disabled";

    $widget = '';
    
    if (!empty($this->errorMessage))
    {
      $widget .= $this->errorMessage.'<br />';
    }  

    $widget .= '<label>'.$this->label.'</label>';
    $widget .='<select '.$disabled.' name="'.$this->name.'">';   

    foreach ($this->options as $key => $option) {       
     $widget .='<option value="'.$key.'"';

     if($key==$this->selected) $widget.='selected';   

     $widget .= '>'.$option.'</option>';
    }

    $widget .='</select>';    
    return $widget;
  }
  
  public function setOptions($options)
  {
   if(is_array($options)){
    $this->options=$options;
   }else{
    throw new \RuntimeException('Les options sont invalides!');
   }
  }

  public function setSelected($selected)
  {
    $this->selected=$selected;
  }

   public function setDisabled($disabled)
  {
    $this->disabled=$disabled;
  }
}