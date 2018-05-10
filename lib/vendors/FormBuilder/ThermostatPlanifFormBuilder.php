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


class ThermostatPlanifFormBuilder extends FormBuilder
{
  public function build()
  {
    $modesRaw=json_decode(json_encode($this->form()->entity()->modes),true);
    $modes=[];
    foreach ($modesRaw as $key => $mode) {

      $modes[$mode["id"]]=$mode["nom"];
    }  
    
    $jour= $this->form()->entity()->jour();
    $nomid=$this->form()->entity()->nomid();

  
    $this->form
    ->add(    
      new SelectField([        
        'label' => 'mode',
        'name' => 'modeid',
        'selected'=> $this->form()->entity()->modeid(),
        'options' =>$modes
        ])
    )->add(    
      new SelectField([
        'label' => 'defaultMode',
        'name' => 'defaultModeid',
        'selected'=> $this->form()->entity()->defaultModeid(),
        'options' =>$modes
       ])
    )->add(    
      new TimePickerField([
        'label' => 'heure1Start',
        'name' => 'heure1Start'    
       ])
    )->add(    
      new TimePickerField([
        'label' => 'heure1Stop',
        'name' => 'heure1Stop'
       ])
    )->add(    
      new TimePickerField([
        'label' => 'heure2Start',
        'name' => 'heure2Start'
       ])
    )->add(    
      new TimePickerField([
        'label' => 'heure2Stop',
        'name' => 'heure2Stop'
       ])
    );


   
  }
}