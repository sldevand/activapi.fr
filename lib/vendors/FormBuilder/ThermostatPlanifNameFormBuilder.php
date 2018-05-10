<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\SelectField;
use \OCFram\TimePickerField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\DateFactory;
use \Debug\Log;


class ThermostatPlanifNameFormBuilder extends FormBuilder
{
  public function build()
  {
  
    $this->form
    ->add(    
      new StringField([        
        'label' => 'nom',
        'name' => 'nom'        
        ]));   
  }
}