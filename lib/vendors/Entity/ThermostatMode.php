<?php
namespace Entity;

use \OCFram\Entity;


class ThermostatMode extends Entity{

	protected $nom;
	protected $consigne;
	protected $delta;

	public function isValid()
  	{
   	 return !(empty($this->nom) || empty($this->consigne) || empty($this->delta));
  	}

	//GETTERS
	public function nom(){return $this->nom;}	
	public function consigne(){return $this->consigne;}
	public function delta(){return $this->delta;}


	//SETTERS
	public function setNom($nom){
		$this->nom=$nom;
	}
		
	public function setConsigne($consigne){

		$this->consigne=$consigne;
	}

	public function setDelta($delta){
		$this->delta=$delta;
	}
	
	public function jsonSerialize(){

		return array(
			'id' => $this->id(),
			'nom' => $this->nom,			
			'consigne' => $this->consigne,
			'delta'=>$this->delta		
		);
	}


}