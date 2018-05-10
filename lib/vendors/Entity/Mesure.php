<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\Sensor;

class Mesure extends Entity{
	protected $id_sensor;
	protected $nom;
	protected $temperature;
	protected $hygrometrie=0.0;
	protected $horodatage;

	//GETTERS
	public function id_sensor(){return $this->id_sensor;}
	public function nom(){return $this->nom;}
	public function temperature(){return $this->temperature;}
	public function hygrometrie(){return $this->hygrometrie;}
	public function horodatage(){return $this->horodatage;}

	//SETTERS
	public function setId_sensor($id_sensor){

		if(!empty($id_sensor) && is_string($id_sensor)){
			$this->id_sensor=$id_sensor;
		}else{
			throw new Exception('idSensor invalide!');
		}
	}

	public function setNom($nom){
		if(!empty($nom) && is_string($nom)){
			$this->$nom=$nom;
		}else{
			throw new Exception('nom invalide!');
		}
	}

	public function setTemperature($temperature){
		$this->temperature=$temperature;
	}

	public function setHygrometrie($hygrometrie){
		$this->hygrometrie=$hygrometrie;
	}

	public function setHorodatage($horodatage){

		if(!empty($horodatage) && is_string($horodatage)){
			$this->horodatage=$horodatage;
		}else{
			throw new Exception('horodatage invalide!');
		}
	}

	public function jsonSerialize(){

		return [
			'nom' => $this->nom,
			'radioid' => $this->id_sensor,	
			'temperature' => $this->temperature,
			'hygrometrie' => $this->hygrometrie,
			'horodatage' => $this->horodatage			
		];
	}
}
