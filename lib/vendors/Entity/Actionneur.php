<?php
namespace Entity;

use \OCFram\Entity;

class Actionneur extends Entity{
	protected $nom;
	protected $module;
	protected $protocole;
	protected $adresse;
	protected $type;
	protected $radioid;
	protected $etat;
	protected $categorie;

	//GETTERS

	public function nom(){return $this->nom;}
	public function module(){return $this->module;}
	public function protocole(){return $this->protocole;}
	public function adresse(){return $this->adresse;}
	public function type(){return $this->type;}
	public function radioid(){return $this->radioid;}
	public function etat(){return $this->etat;}
	public function categorie(){return $this->categorie;}

	//SETTERS

	public function setNom($nom){
		$this->nom=$nom;
	}

	public function setModule($module){
		$this->module=$module;
	}

	public function setProtocole($protocole){
		$this->protocole=$protocole;
	}
	
	public function setAdresse($adresse){
		$this->adresse=$adresse;
	}	
	
	public function setType($type){
		$this->type=$type;
	}
		
	public function setRadioid($radioid){

		$this->radioid=$radioid;
	}

	public function setEtat($etat){
		$this->etat=$etat;
	}
	
	public function setCategorie($categorie){
		$this->categorie=$categorie;
	}

	
	public function jsonSerialize(){

		return array(
			'id' => $this->id(),
			'nom' => $this->nom,
			'module' => $this->module,
			'protocole' => $this->protocole,
			'adresse' => $this->adresse,
			'type'=>$this->type,
			'radioid' => $this->radioid,
			'etat'=>$this->etat,
			'categorie'=>$this->categorie
		);
	}
}
