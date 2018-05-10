<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\Actionneur;

class Scenario extends Entity{
	
	protected $nom;
	protected $actionneur;
	protected $scenarioid;
	protected $actionneurid;
	protected $etat;


	//GETTERS
	public function nom(){return $this->nom;}
	public function actionneur(){return $this->actionneur;}	
	public function scenarioid(){return $this->scenarioid;}
	public function actionneurid(){return $this->actionneurid;}	
	public function etat(){return $this->etat;}


	//SETTERS

	public function setNom($nom){
		$this->nom=$nom;		
	}
	
	public function setActionneur(Actionneur $actionneur){		
		$this->actionneur=$actionneur;
	}

	public function setScenarioid($scenarioid){
		$this->scenarioid=$scenarioid;		
	}

	public function setActionneurid($actionneurid){
		$this->actionneurid=$actionneurid;
	}

	public function setEtat($etat){
		$this->etat=$etat;
	}
	
	public function jsonSerialize(){

		return array(	
			'id' => $this->id,			
			'nom' => $this->nom,		
			'actionneur' => $this->actionneur,			
			'scenarioid' => $this->scenarioid,
			'etat'=>$this->etat
		);
	}
}
