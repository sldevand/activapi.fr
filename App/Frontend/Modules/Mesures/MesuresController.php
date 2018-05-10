<?php
namespace App\Frontend\Modules\Mesures;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Mesure;
use \Materialize\WidgetFactory;
use \Debug\Log;


class MesuresController extends BackController
{   
  public function executeIndex(HTTPRequest $request)
  {

    $this->page->addVar('title', 'Gestion des mesures');

    $managerMesures = $this->managers->getManagerOf('Mesures');	 
    
  	$nDernieresMesures=10;

  	if($request->getData("nbMesures")){
  		if($request->getData("nbMesures")>100) {$nDernieresMesures=100;}
  		else{$nDernieresMesures=$request->getData("nbMesures");}
  	}

    $cards=[];
    //Mesures
    $listeMesures=$managerMesures->getList(0,$nDernieresMesures);
    $nombreMesures=$managerMesures->count();
    $cards[]=$this->makeMesuresWidget($listeMesures,$nombreMesures,$nDernieresMesures);

      
    $this->page->addVar('cards', $cards);

  }  

  public function makeMesuresWidget($listeMesures,$nbMesures,$nDernieresMesures){
      $domId='Mesures';
     
      $table=WidgetFactory::makeTable($domId,$listeMesures);
      $cardContent='<hr><span>Nombre de mesures : '.$nbMesures.'</br>';
      $cardContent.='Voici la liste des '.$nDernieresMesures.' dernières Mesures</span><hr>';
      $cardContent.=$table->getHtml();
      return WidgetFactory::makeCard($domId,$domId,$cardContent);

  }

}
