<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\NumberField;
use \OCFram\NotNullValidator;
use \Debug\Log;


class SensorsFormBuilder extends FormBuilder
{
  public function build()
  {     
   
    $this->form
    ->add(    
      new StringField([        
        'label' => 'radioid',
        'name' => 'radioid',
        'required' => 'true'
        ])
    )->add(    
      new StringField([
        'label' => 'nom',
        'name' => 'nom',     
        'required' => 'true'  
       ])
    )->add(    
      new StringField([
        'label' => 'categorie',
        'name' => 'categorie',    
        'required' => 'true'    
       ])
    )->add(    
      new StringField([
        'label' => 'radioaddress',
        'name' => 'radioaddress',    
        'required' => 'true'    
       ])    
    );

   
  }
}