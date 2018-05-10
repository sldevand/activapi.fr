<?php
namespace App\Frontend\Modules\ThermostatPlanif;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\DateFactory;
use \OCFram\FormBuilder;
use \OCFram\FormHandler;
use \Entity\ThermostatPlanif;
use \Entity\ThermostatMode;
use \Materialize\WidgetFactory;
use \Materialize\Link;
use \Materialize\FlatButton;
use \Materialize\FloatingActionButton;
use \FormBuilder\ThermostatPlanifFormBuilder;
use \FormBuilder\ThermostatPlanifNameFormBuilder;
use \Debug\Log;

class ThermostatPlanifController extends BackController{   

  public function executeIndex(HTTPRequest $request){
    $this->page->addVar('title', 'Gestion du Planning');

    $manager = $this->managers->getManagerOf('ThermostatPlanif');     
    $thermostatPlanningsContainer=$manager->getListArray();
    
    $cards=[];
      
     foreach($thermostatPlanningsContainer as $thermostatPlannings){         
     
      $thermostatDatas=[];
        foreach($thermostatPlannings as  $thermostatPlanningObj){
         $thermostatPlanning = json_decode(json_encode($thermostatPlanningObj),true);        

         //DATA PREPARE FOR TABLE        
          $hideColumns=['id','nomid','nom','modeid','defaultModeid'];  
          $thermostatPlanning["jour"]=DateFactory::toStrDay($thermostatPlanning['jour']);
          $thermostatPlanning["mode"]=$thermostatPlanning["mode"]["nom"];
          $thermostatPlanning["defaultMode"]=$thermostatPlanning["defaultMode"]["nom"];
          $linkEdit=new Link('',"../activapi.fr/thermostat-planif-edit-".$thermostatPlanning["id"],'edit','primaryTextColor');
          $thermostatPlanning["editer"]= $linkEdit->getHtmlForTable();        
          $domId=$thermostatPlanning["nom"]["nom"];
          $thermostatDatas[]=$thermostatPlanning;   
       }

     $table=WidgetFactory::makeTable($domId,$thermostatDatas,true,$hideColumns); 
      
      $cardTitle='Thermostat : Planning  '.$domId; 
      $linkDelete=new Link('Supprimer ce Planning',"../activapi.fr/thermostat-planif-delete-".$thermostatPlanning["nomid"],'delete','secondaryTextColor');

      $cardContent=$linkDelete->getHtml();
      $cardContent.=$table->getHtml();    
      
      $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
      $cards[]=$card;
   }   
    
    $addPlanifFab = new FloatingActionButton([
       'id'=>"addPlanifFab",
       'fixed'=>true,
       'icon'=>"add",
       'href'=>"../activapi.fr/thermostat-planif-add"
      ]);

    $this->page->addVar('cards', $cards);     
    $this->page->addVar('addPlanifFab', $addPlanifFab);
  }  

  function executeAdd(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('ThermostatPlanif');     
    $domId= 'Ajout';
    $cardTitle='Thermostat : Planning  '.$domId; 
    $cardContent='';
    $name=null;

    if ($request->method() == 'POST'){
      if($request->postExists('nom')){      
       $name=$request->postData('nom');
      }
      
      if(!is_null($name)){
        $result = $manager->addPlanifTable($name);
        if($result>0){
          $cardContent='<p class="flow-text">OK</p>';
        }else{
          $cardContent="Ce nom existe déjà!";   
        }
      }else{
        $cardContent="Le nom est vide";
      }
    }

    $item=new ThermostatPlanif(['nom'=>$name]);
    $fb = new ThermostatPlanifNameFormBuilder($item);
    $fb->build();
    $form = $fb->form();
    
    $cardContent='<form action="" method="post">';
    $cardContent.=$form->createView();
    $cardContent.='<input class="btn-flat" type="submit" value="Valider" />';
    $cardContent.='</form>';
    $fh = new FormHandler($form ,$manager,$request);

    if($fh->process()){
      $this->app->httpResponse()->redirect('../activapi.fr/thermostat-planif');
    }
    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
    $this->page->addVar('card', $card);   
  }

   public function executeEdit(HTTPRequest $request)
  {
    $manager = $this->managers->getManagerOf('ThermostatPlanif'); 
    $modes=$manager->getModes();

    if ($request->method() == 'POST'){
      
      $item = new ThermostatPlanif([
        'jour' => $request->postData('jour'),
        'modeid' => $request->postData('modeid'),
        'defaultModeid' => $request->postData('defaultModeid'),
        'heure1Start' => $request->postData('heure1Start'),
        'heure1Stop' => $request->postData('heure1Stop'),
        'heure2Start' => $request->postData('heure2Start'),
        'heure2Stop' => $request->postData('heure2Stop'),
        'nomid' => $request->postData('nomid')
      ]);

    if ($request->getExists('id'))
      {
        $id=$request->getData('id');
        $item->setId($id);
      }

    }else{  
      if ($request->getExists('id'))
      {

        $id=$request->getData("id");       
        $item = $manager->getUnique($id);
      }
    }
    $cards=[];    
   

    $domId='Edition';
    $item->modes=$modes;   

    $tpfb = new ThermostatPlanifFormBuilder($item);
    $tpfb->build();
    $form = $tpfb->form();
  

    $cardContent='<form action="" method="post">';
    $cardContent.=$form->createView();
    $cardContent.='<input class="btn-flat" type="submit" value="Valider" />';
    $cardContent.='</form>';
    $fh = new FormHandler($form ,$manager,$request);

    if ($fh->process()){    
      $this->app->httpResponse()->redirect('../activapi.fr/thermostat-planif');
    }
    
    $link=new Link("Edition",
                    "../activapi.fr/thermostat-planif",
                    "arrow_back",
                    "white-text",
                    "white-text");

      $cardTitle=$link->getHtml();

      $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);
      $cards[]=$card;   
     
      
       
    $this->page->addVar('title', 'Edition du Planning');
    $this->page->addVar('cards', $cards);   

  }

   public function executeDelete(HTTPRequest $request){

    $manager = $this->managers->getManagerOf('ThermostatPlanif'); 
    
    $domId='Suppression';
    $nom='';  
   if ($request->method() == 'POST'){

      if ($request->getExists('id')){
        $id=$request->getData('id');
        $manager->delete($id);
        $this->app->httpResponse()->redirect('../activapi.fr/thermostat-planif');
      }
    }else{


      if ($request->getExists('id')){
        $id=$request->getData('id');
        $nom=$manager->getNom($id);
      }
    }

    $subButton=new FlatButton(['id'=>'Delete','title'=>'Supprimer','icon'=>'delete','type'=>'submit','color'=>'secondaryTextColor']);

    $cardContent='<p class="flow-text">Supprimer le planning '. $nom->nom() .' ?</p>
                  <br>
                  <p>
                    <form action="" method="post">
                    '. $subButton->getSubmitHtml() .'
                    </form>
                  </p>';

  
                    
      
    $link=new Link($domId,
                    "../activapi.fr/thermostat-planif",
                    "arrow_back",
                    "white-text",
                    "white-text");

    $cardTitle=$link->getHtml();

    $card=WidgetFactory::makeCard($domId,$cardTitle,$cardContent);

    $this->page->addVar('title', 'Suppression du Planning');
    $this->page->addVar('card', $card); 

   }
}
