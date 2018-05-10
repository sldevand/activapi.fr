<?php
namespace App\Frontend\Modules\ThermostatModes;

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

    $this->page->addVar('title', 'Gestion des Modes');
    $manager = $this->managers->getManagerOf('ThermostatModes'); 
  
    $modes=json_decode(json_encode($manager->getList()),TRUE);
    $modesData=[];
    $domId="Modes";
    $hideColumns=['id'];  

    foreach($modes as $mode){
        //DATA PREPARE FOR TABLE
        $linkEdit=new Link('',"../activapi.fr/thermostat-modes-edit-".$mode["id"],'edit','primaryTextColor');
        $linkDelete=new Link('',"../activapi.fr/thermostat-modes-delete-".$mode["id"],'delete','secondaryTextColor');
        $mode["editer"]= $linkEdit->getHtmlForTable();        
        $mode["supprimer"]= $linkDelete->getHtmlForTable();             
        $modesData[]=$mode;          
      }

  
      $table=WidgetFactory::makeTable($domId,$modesData,false,$hideColumns); 

      $cardTitle=$domId; 
      $cardContent=$table->getHtml();    
      $addModeFab = new FloatingActionButton([
       'id'=>"addModeFab",
       'fixed'=>true,
       'icon'=>"add",
       'href'=>"../activapi.fr/thermostat-modes-add"
      ]);

      $cardContent.= $addModeFab->getHtml();
      $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
      $cards[]=$card;

      $this->page->addVar('cards',$cards);
  
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

    $cardContent='<p class="flow-text">Voulez-vous vraiment supprimer ce mode?</p>';
    $cardContent.='<form action="" method="post">';
    $cardContent.='<input class="btn-flat" type="submit" value="Supprimer" />';   
    $cardContent.='</form>';

      
    $link=new Link($domId,
                    "../activapi.fr/thermostat-modes",
                    "arrow_back",
                    "white-text",
                    "white-text");

    $cardTitle=$link->getHtml();

    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);

    $this->page->addVar('title', 'Suppression du Mode');
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
  
    $cards=[];   

    $tmfb = new ThermostatModesFormBuilder($item);
    $tmfb->build();
    $form = $tmfb->form();  

    $cardContent='<form action="" method="post">';
    $cardContent.=$form->createView();
    $cardContent.='<input class="btn-flat" type="submit" value="Valider" />';
    $cardContent.='</form>';
    $fh = new FormHandler($form ,$manager,$request);

    if ($fh->process()){    
      $this->app->httpResponse()->redirect('../activapi.fr/thermostat-modes');
    }
    
    $link=new Link($domId,
                    "../activapi.fr/thermostat-modes",
                    "arrow_back",
                    "white-text",
                    "white-text");

    $cardTitle=$link->getHtml();

    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
    $cards[]=$card;        
       
    $this->page->addVar('title', 'Edition du Mode');
    $this->page->addVar('cards', $cards);   

  } 

}
