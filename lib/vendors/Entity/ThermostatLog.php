<?php
namespace Entity;

use \OCFram\Entity;


class ThermostatLog extends Entity{

	protected $horodatage;
	protected $etat;
	protected $consigne;
	protected $delta;

	//GETTERS
	public function horodatage(){return $this->horodatage;}
	public function etat(){return $this->etat;}	
	public function consigne(){return $this->consigne;}
	public function delta(){return $this->delta;}

	//SETTERS
	public function setHorodatage(\DateTime $horodatage){

		$this->horodatage=$horodatage;
	}

	public function setEtat($etat){
		$this->etat=$etat;
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
			'horodatage' => $this->horodatage,
			'etat' => $this->etat,
			'consigne' => $this->consigne,
			'delta'=>$this->delta
		);
	}

	
}