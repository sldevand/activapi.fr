<?php
namespace Entity;

use \OCFram\Entity;

class ThermostatPlanifNom extends Entity{

	
	protected $nom;	

	public function isValid()
  	{
   	 return !( empty("nom") 	 		
   	 	);
  	}

	//GETTERS
	
	public function nom(){return $this->nom;}	

	//SETTERS
	
	public function setNom($nom){
		$this->nom=$nom;
	}	
	
	public function jsonSerialize(){
		return array(
			'id' => $this->id(),		
			'nom' => $this->nom
			);
	}


}