<?php
namespace Entity;

use \OCFram\Entity;

class Sensor extends Entity{

	protected $radioid;
	protected $releve;
	protected $actif;
	protected $valeur1;
	protected $valeur2;
	protected $nom;
	protected $categorie;
	protected $radioaddress;

	//GETTERS
	public function radioid(){return $this->radioid;}
	public function releve(){return $this->releve;}
	public function actif(){return $this->actif;}
	public function valeur1(){return $this->valeur1;}
	public function valeur2(){return $this->valeur2;}
	public function nom(){return $this->nom;}
	public function categorie(){return $this->categorie;}
	public function radioaddress(){return $this->radioaddress;}

	//SETTERS

	public function setRadioid($radioid){

		if(!empty($radioid) && is_string($radioid)){
			$this->radioid=$radioid;
		}else{
			throw new Exception('radioid invalide!');
		}
	}

	public function setReleve($releve){
		$this->releve=$releve;
	}

	public function setActif($actif){
		$this->actif=$actif;
	}

	public function setValeur1($valeur1){
		$this->valeur1=$valeur1;
	}

	public function setValeur2($valeur2){
		$this->valeur2=$valeur2;
	}

	public function setNom($nom){

		if(!empty($nom) && is_string($nom)){
			$this->nom=$nom;
		}else{
			throw new Exception('nom invalide!');
		}
	}

	public function setCategorie($categorie){

		if(!empty($categorie) && is_string($categorie)){
			$this->categorie=$categorie;
		}else{
			throw new Exception('categorie invalide!');
		}
	}

	public function setRadioaddress($radioaddress){

		if(!empty($radioaddress) && is_string($radioaddress)){
			$this->radioaddress=$radioaddress;
		}else{
			throw new Exception('radioaddress invalide!');
		}
	}

	 	
	public function jsonSerialize(){

		return array(
			'id' => $this->id(),
			'radioid' => $this->radioid,
			'releve' => $this->releve,
			'actif' => $this->actif,
			'valeur1' => $this->valeur1,
			'valeur2' => $this->valeur2,
			'nom' => $this->nom,
			'categorie' => $this->categorie,
			'radioaddress' => $this->radioaddress
		);
	}
}
