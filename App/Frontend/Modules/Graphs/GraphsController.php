<?php
namespace App\Frontend\Modules\Graphs;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \OCFram\DateFactory;
use \SFram\JSTranslator;
use \Materialize\Card;
use \SFram\OSDetectorFactory;

class GraphsController extends BackController
{
  public function executeIndex(HTTPRequest $request){

    $this->page->addVar('title', 'Gestion des Graphs');   

	$key = OSDetectorFactory::getApiAddressKey();

	$apiBaseAddress=$this->app()->config()->get($key);

    $sensorid=$request->getData("id_sensor");
	$dateMin=$request->getData("dateMin");
	$dateMax=$request->getData("dateMax");
	$today=DateFactory::todayToString();

	if(is_null($dateMin) || $dateMin==""){$dateMin=$today;}
	if(is_null($dateMax) || $dateMax==""){$dateMax=$today;}

	$manager = $this->managers->getManagerOf('Mesures');
	$listeSensors = $manager-> getSensors('thermo');
	$listeThermostat = $manager-> getSensors('thermostat');	

	$sensorids=[];
	foreach ($listeSensors as $sensor) {
		$sensorids[]=$sensor->radioid();
	}

	foreach ($listeThermostat as $thermostat) {
		$sensorids[]=$thermostat->radioid();
	}		

	$apiURL=$apiBaseAddress."api/mesures/";	

	$tempMin=10;
	$tempMax=25;

	if($sensorid=="sensor24ctn10id3"){
		$tempMin=-5;
		$tempMax=20;
	}

	$dateMinFr = DateFactory::toFrDate($dateMin);
	$dateMaxFr = DateFactory::toFrDate($dateMax);

	//GRAPH CARD CREATION
	$cardTitle='Températures';
	$cardContent='';
	if($dateMin==$dateMax ){		
		
		if($dateMin==$today){
			$cardContent.= "Aujourd'hui";
		}else{
			$cardContent.="$dateMinFr";
		}
	}else{
		$cardContent.= "Températures du $dateMinFr au $dateMaxFr";
	}	
	$graphId="tempGraph";
	$graphCard=$this->makeGraphCard($cardTitle,$cardContent,$graphId);

	$jst = new JSTranslator(['apiURL'=>$apiURL,
							
							'sensorid'=>$sensorid,
							'sensorids'=>$sensorids,
							'dateMin'=>$dateMin,
							'dateMax'=>$dateMax,
							'tempMin'=>$tempMin,
							'tempMax'=>$tempMax,
							'graphId'=> $graphId]);

	$this->page->addVar('jst',$jst);
	$this->page->addVar('graphId', $graphId);
    $this->page->addVar('graphCard', $graphCard->getHtml());       

  }

  public function makeGraphCard($cardTitle,$cardContent,$graphId){

	$cardOpt=[
			'id'=>'graphCard',
			'bgColor'=>'primaryLightColor',
			'textColor'=>'textOnPrimaryColor',
			'title'=>$cardTitle];

	$card=new Card($cardOpt);

	$cardContent.='<canvas id="'.$graphId.'" width=500 height=500></canvas>';
	
	$card->setContent($cardContent);

	return $card;
  }

}
