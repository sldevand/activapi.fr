<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\ThermostatMode;
use \Entity\Sensor;

class Thermostat extends Entity{
	protected $nom;
	protected $modeid;
	protected $mode;
	protected $sensorid;
	protected $sensor;
	protected $planning;
	protected $planningName;
	protected $manuel;
	protected $consigne;
	protected $delta;
	protected $interne;
	protected $etat;
	protected $releve;
	protected $temperature;
	protected $hygrometrie;


	//GETTERS

	public function nom(){return $this->nom;}
	public function modeid(){return $this->modeid;}
	public function mode(){return $this->mode;}
	public function sensorid(){return $this->sensorid;}
	public function sensor(){return $this->sensor;}
	public function planning(){return $this->planning;}
	public function planningName(){return $this->planningName;}
	public function manuel(){return $this->manuel;}
	public function consigne(){return $this->consigne;}
	public function delta(){return $this->delta;}
	public function interne(){return $this->interne;}
	public function etat(){return $this->etat;}
	public function releve(){return $this->releve;}
	public function temperature(){return $this->temperature;}
	public function hygrometrie(){return $this->hygrometrie;}

	//SETTERS



	public function setNom($nom){
		$this->nom=$nom;
	}

	public function setModeid($modeid){
		$this->modeid=$modeid;
	}

	public function setMode(ThermostatMode $mode){
		$this->mode=$mode;
	}

	public function setSensorid($sensorid){
		$this->sensorid=$sensorid;
	}

	public function setSensor(Sensor $sensor){
		$this->sensor=$sensor;
	}

	public function setPlanning($planning){
		$this->planning=$planning;
	}

	public function setPlanningName($planningName){
		$this->planningName=$planningName;
	}

	public function setManuel($manuel){
		$this->manuel=$manuel;
	}

	public function setConsigne($consigne){

		$this->consigne=$consigne;
	}

	public function setDelta($delta){
		$this->delta=$delta;
	}

	public function setInterne($interne){
		$this->interne=$interne;
	}

	public function setEtat($etat){
		$this->etat=$etat;
	}

	public function setReleve($releve){
		$this->releve=$releve;
	}

	public function setTemperature($temperature){
		$this->temperature=$temperature;
	}

	public function setHygrometrie($hygrometrie){
		$this->hygrometrie=$hygrometrie;
	}

	public function jsonSerialize(){

		return array(
			'id' => $this->id(),
			'nom' => $this->nom,
			'mode' => $this->mode,
			'sensor' => $this->sensor,
			'modeid' => $this->modeid,
			'sensorid' => $this->sensorid,
			'planning' => $this->planning,
			'planningName' => $this->planningName,
			'manuel'=>$this->manuel,
			'consigne' => $this->consigne,
			'delta'=>$this->delta,
			'interne'=>$this->interne,
			'etat'=>$this->etat,
			'releve'=>$this->releve,
			'temperature'=>$this->temperature,
			'hygrometrie'=>$this->hygrometrie
		);
	}

	public function hasChanged(Thermostat $thermostat){
		return ($thermostat->etat()!=$this->etat() || $thermostat->consigne()!=$this->consigne() || $thermostat->delta()!=$this->delta());
	}
}
