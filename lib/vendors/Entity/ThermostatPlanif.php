<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\ThermostatMode;

class ThermostatPlanif extends Entity{

	protected $nomid;
	protected $nom;
	protected $jour;
	protected $modeid;
	protected $mode;
	protected $defaultModeid;
	protected $defaultMode;
	protected $heure1Start;
	protected $heure1Stop;
	protected $heure2Start;
	protected $heure2Stop;

	public function isValid()
  	{
   	 return !( empty("nom") 	 		
   	 	);
  	}

	//GETTERS
	public function nomid(){return $this->nomid;}
	public function nom(){return $this->nom;}	
	public function jour(){return $this->jour;}	
	public function modeid(){return $this->modeid;}	
	public function mode(){return $this->mode;}	
	public function defaultModeid(){return $this->defaultModeid;}	
	public function defaultMode(){return $this->defaultMode;}	
	public function heure1Start(){return $this->heure1Start;}	
	public function heure1Stop(){return $this->heure1Stop;}	
	public function heure2Start(){return $this->heure2Start;}	
	public function heure2Stop(){return $this->heure2Stop;}	


	//SETTERS
	public function setNomid($nomid){
		$this->nomid=$nomid;
	}

	public function setNom($nom){
		$this->nom=$nom;
	}

	public function setJour($jour){
		$this->jour=$jour;
	}

	public function setModeid($modeid){
		$this->modeid=$modeid;
	}

	public function setMode(ThermostatMode $mode){
		$this->mode=$mode;
	}

	public function setDefaultModeid($defaultModeid){
		$this->defaultModeid=$defaultModeid;
	}

	public function setDefaultMode(ThermostatMode $defaultMode){
		$this->defaultMode=$defaultMode;
	}

	public function setHeure1Start($heure1Start){
		$this->heure1Start=$heure1Start;
	}

	public function setHeure1Stop($heure1Stop){
		$this->heure1Stop=$heure1Stop;
	}

	public function setHeure2Start($heure2Start){
		$this->heure2Start=$heure2Start;
	}

	public function setHeure2Stop($heure2Stop){
		$this->heure2Stop=$heure2Stop;
	}



	
	
	public function jsonSerialize(){
		return array(
			'id' => $this->id(),
			'nomid' => $this->nomid,
			'nom' => $this->nom,
			'jour' => $this->jour,
			'modeid' => $this->modeid,
			'mode' => $this->mode,
			'defaultModeid' => $this->defaultModeid,
			'defaultMode' => $this->defaultMode,
			'heure1Start' => $this->heure1Start,
			'heure1Stop' => $this->heure1Stop,
			'heure2Start' => $this->heure2Start,
			'heure2Stop' => $this->heure2Stop			
		);
	}


}