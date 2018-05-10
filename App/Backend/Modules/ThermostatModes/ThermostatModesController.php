<?php
namespace App\Backend\Modules\ThermostatModes;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\DateFactory;
use \OCFram\FormBuilder;
use \OCFram\FormHandler;
use \Entity\ThermostatMode;
use \Materialize\WidgetFactory;
use \Materialize\Link;
use \Materialize\FloatingActionButton;
use \FormBuilder\ThermostatModesFormBuilder;

use \Debug\Log;

class ThermostatModesController extends BackController
{ 
  public function executeIndex(HTTPRequest $request){
    $manager = $this->managers->getManagerOf('ThermostatModes');  


    if($request->getExists('id')){
      $id = $request->getData('id');        
    }    

    if(!is_null($id) && !empty($id)){
      $modes =  $manager->getUnique($id);
    }else{
      $modes =  $manager->getList();  
    }
      $this->page->addVar('modes', $modes);  
  }

   public function executeDelete(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('ThermostatModes'); 
    
    $domId='Suppression';  
   if ($request->method() == 'POST'){

      if ($request->getExists('id')){
        $id=$request->getData('id');
        $manager->delete($id);
        $this->app->httpResponse()->redirect('../activapi.fr/thermostat-modes');
      }
    }

    $this->page->addVar('card', $card); 

   }
   
  public function executeEdit(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('ThermostatModes'); 
    $modes=$manager->getList();
    $domId='Edition';  
    if ($request->method() == 'POST'){
      
      $item = new ThermostatMode([
        'nom' => $request->postData('nom'),
        'consigne' => $request->postData('consigne'),
        'delta' => $request->postData('delta')        
      ]);    

     if ($request->getExists('id')){
        $id=$request->getData('id');
        $item->setId($id);
      }

    }else{  
      if ($request->getExists('id'))
      {
        $id=$request->getData("id");       
        $item = $manager->getUnique($id);
      }else{
        $domId='Ajout';  
        $item = new ThermostatMode();

      }
    }  
  
    $this->page->addVar('cards', $cards);   

  } 

}
