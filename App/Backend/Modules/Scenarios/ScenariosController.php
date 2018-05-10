<?php
namespace App\Backend\Modules\Scenarios;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Scenario;
use \Entity\Actionneur;

class ScenariosController extends BackController
{

  public function executeDelete(HTTPRequest $request)
  {
    $id = $request->postData('id');
    $this->managers->getManagerOf('Scenarios')->delete($id);
    $this->page->addVar('result', 'Scenario '.$id.'Supprimé');
  }

  public function executeDeleteitem(HTTPRequest $request)
  {
    $id = $request->postData('id');
    $this->managers->getManagerOf('Scenarios')->deleteItem($id);
    $this->page->addVar('result', 'Item '.$id.'Supprimé');
  }


  public function executeIndex(HTTPRequest $request)
  {
	 
	$id=$request->getData("id");  	 
	
	$scenarioManager = $this->managers->getManagerOf('Scenarios');
	$actionneursManager = $this->managers->getManagerOf('Actionneurs');
	
	if(empty($id)){ $listeScenarios = $scenarioManager->getList();}
	else{$listeScenarios = $scenarioManager->getScenario($id);}
	
	$scenariosTab=[];

	foreach($listeScenarios as $key=>$scenario){
		$actionneur = $actionneursManager->getUnique($scenario->actionneurid());
		$listeScenarios[$key]->setActionneur($actionneur);
		$actionneur->setEtat($listeScenarios[$key]->etat());
		$scenariosTab[$scenario->scenarioid()]["nom"]=$listeScenarios[$key]->nom();	
		$scenariosTab[$scenario->scenarioid()]["scenarioid"]=$scenario->scenarioid();

		$tempActionneur = $listeScenarios[$key]->actionneur();		
		$scenariosTab[$scenario->scenarioid()]["data"][$scenario->id()]=$tempActionneur;

	}

	$this->page->addVar('scenarios', $scenariosTab);	
}

  public function executeInsert(HTTPRequest $request)
  {
    	$manager = $this->managers->getManagerOf('Scenarios');
		
		$scenario = new Scenario(
		[
			'nom'=>$request->postData('nom'),
			'actionneurid'=>$request->postData('actionneurid'),
			'etat'=>$request->postData('etat')
		
		]);
		
		
	$add=$manager->add($scenario);

	$this->page->addVar('result', $add);	
  }



  public function executeUpdate(HTTPRequest $request)
  {
      
	$manager = $this->managers->getManagerOf('Scenarios');

	$scenario = new Scenario(
	[	
		'id'=>$request->postData('id'),
		'nom'=>$request->postData('nom'),
		'scenarioid'=>'',
		'actionneurid'=>'',
		'etat'=>''
	]);
    
    $this->page->addVar('result',$manager->update($scenario));
  }

  public function executeUpdateitem(HTTPRequest $request)
  {

  	$manager = $this->managers->getManagerOf('Scenarios');

	$scenario = new Scenario(
	[	
		'id'=>$request->postData('id'),
		'nom'=>$request->postData('nom'),
		'scenarioid'=>$request->postData('scenarioid'),
		'actionneurid'=>$request->postData('actionneurid'),
		'etat'=>$request->postData('etat')
	]);
    
     $this->page->addVar('result',$manager->updateItem($scenario));
  }



}
