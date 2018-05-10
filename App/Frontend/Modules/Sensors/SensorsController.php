<?php
namespace App\Frontend\Modules\Sensors;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\FormHandler;
use \Entity\Sensor;
use \Materialize\WidgetFactory;
use \Materialize\Link;
use \Materialize\FloatingActionButton;
use \FormBuilder\SensorsFormBuilder;
use \Debug\Log;



class SensorsController extends BackController
{   
  public function executeIndex(HTTPRequest $request)
  {

    $this->page->addVar('title', 'Gestion des sensors');
  	
    $manager = $this->managers->getManagerOf('Sensors'); 	

    $cards=[];

    //Sensors
    $listeSensors= $manager->getList();
    $cards[]=$this->makeSensorsWidget($listeSensors);
    $addSensorsFab = new FloatingActionButton([
     'id'=>"addSensorsFab",
     'fixed'=>true,
     'icon'=>"add",
     'href'=>"../activapi.fr/sensors-add"
    ]);   

    $this->page->addVar('cards', $cards);
    $this->page->addVar('addSensorsFab', $addSensorsFab);

  } 

  public function executeDelete(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('Sensors'); 
    
    $domId='Suppression';  
   if ($request->method() == 'POST'){

      if ($request->getExists('id')){
        $id=$request->getData('id');
        $manager->delete($id);
        $this->app->httpResponse()->redirect('../activapi.fr/sensors');
      }
    }

    $cardContent='<p class="flow-text">Voulez-vous vraiment supprimer ce sensor?</p>';
    $cardContent.='<form action="" method="post">';
    $cardContent.='<input class="btn-flat" type="submit" value="Supprimer" />';   
    $cardContent.='</form>';

      
    $link=new Link($domId,
                    "../activapi.fr/sensors",
                    "arrow_back",
                    "white-text",
                    "white-text");

    $cardTitle=$link->getHtml();

    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);

    $this->page->addVar('title', "Suppression du Sensor");
    $this->page->addVar('card', $card); 

   }
   
  public function executeEdit(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('Sensors'); 
    $sensors=$manager->getList();
    $domId='Edition';  

    if ($request->method() == 'POST'){
      
      $item = new Sensor([
        'radioid' => $request->postData('radioid'),        
        'nom' => $request->postData('nom'),
        'categorie' => $request->postData('categorie'),
        'radioaddress' => $request->postData('radioaddress'),
        'releve' => "",        
        'actif' => "",
        'valeur1' => "",
        'valeur2' => ""
      ]);    

     if ($request->getExists('id')){
        $id=$request->getData('id');
        $prevItem = $manager->getUnique($id);

        $item->setId($id);
        $item->setReleve($prevItem->releve());
        $item->setActif($prevItem->actif());
        $item->setValeur1($prevItem->valeur1());
        $item->setValeur2($prevItem->valeur2());
      }

    }else{  
      if ($request->getExists('id'))
      {
        $id=$request->getData("id");       
        $item = $manager->getUnique($id);
      }else{
        $domId='Ajout';  
        $item = new Sensor();

      }
    }
  
    $cards=[];   

    $tmfb = new SensorsFormBuilder( $item);
    $tmfb->build();
    $form = $tmfb->form();  

    $cardContent='<form action="" method="post">';
    $cardContent.=$form->createView();
    $cardContent.='<input class="btn-flat" type="submit" value="Valider" />';
    $cardContent.='</form>';
    $fh = new FormHandler($form ,$manager,$request);

    if ($fh->process()){    
      $this->app->httpResponse()->redirect('../activapi.fr/sensors');
    }
    
    $link=new Link($domId,
                    "../activapi.fr/sensors",
                    "arrow_back",
                    "white-text",
                    "white-text");

    $cardTitle=$link->getHtml();

    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
    $cards[]=$card;        
       
    $this->page->addVar('title', 'Edition du Sensor');
    $this->page->addVar('cards', $cards);   

  }

  public function makeSensorsWidget($sensors){
      $domId='Sensors';     

      $sensors=json_decode(json_encode($sensors),TRUE);
      $sensorsData=[];        

    foreach($sensors as $sensor){
        //DATA PREPARE FOR TABLE
        $linkEdit=new Link('',"../activapi.fr/sensors-edit-".$sensor["id"],'edit','primaryTextColor');
        $linkDelete=new Link('',"../activapi.fr/sensors-delete-".$sensor["id"],'delete','secondaryTextColor');
        $sensor["editer"]= $linkEdit->getHtmlForTable();        
        $sensor["supprimer"]= $linkDelete->getHtmlForTable();             
        $sensorsData[]=$sensor;          
      }


      $table=WidgetFactory::makeTable($domId,$sensorsData);     
      return WidgetFactory::makeCard($domId,$domId,$table->getHtml());

  }



}
