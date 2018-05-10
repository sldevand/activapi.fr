<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\NumberField;
use \OCFram\NotNullValidator;
use \Debug\Log;


class ActionneursFormBuilder extends FormBuilder
{
  public function build()
  {     
   
    $this->form
    ->add(    
      new StringField([        
        'label' => 'nom',
        'name' => 'nom',
        'required' => 'true'
        ])
    )->add(    
      new StringField([
        'label' => 'module',
        'name' => 'module',     
        'required' => 'true'  
       ])
    )->add(    
      new StringField([
        'label' => 'protocole',
        'name' => 'protocole',    
        'required' => 'true'    
       ])
    )->add(    
      new StringField([
        'label' => 'adresse',
        'name' => 'adresse',    
        'required' => 'true'    
       ])
    )->add(    
      new StringField([
        'label' => 'type',
        'name' => 'type',    
        'required' => 'true'    
       ])
    )->add(    
      new StringField([
        'label' => 'radioid',
        'name' => 'radioid',    
        'required' => 'true'    
       ])
    )->add(    
      new NumberField([
        'label' => 'etat',
        'name' => 'etat',    
        'min' => 0,
        'max' => 255,
        'required' => 'true'    
       ])
    )->add(    
      new StringField([
        'label' => 'categorie',
        'name' => 'categorie',    
        'required' => 'true'    
       ])
    );

   
  }
}