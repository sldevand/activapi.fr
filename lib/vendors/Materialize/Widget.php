<?php
namespace Materialize;

abstract class Widget{
	
	protected $_id;
	
	public function __construct(array $donnees){		
		$this->hydrate($donnees);
			
	}
	
	public function hydrate(array $donnees)
	{		
		foreach ($donnees as $key => $value){	
				
		// On récupère le nom du setter correspondant à l'attribut.
		$method = 'set'.ucfirst($key);   

		// Si le setter correspondant existe.
			if (method_exists($this, $method)){
				// On appelle le setter.
				$this->$method($value);			
			}
		} 		
	}
	
	public function id(){return $this->_id;}
	
	public function setId($id){
		$this->_id=$id;
	}
	
	abstract public function getHtml();
	
	
	
}