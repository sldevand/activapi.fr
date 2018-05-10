<?php

namespace SFram;

use \SFram\DaoColumn;
use \OCFram\Entity;
use \Debug\Log;

class DaoTable {

	protected $name;
	protected $columns;

	//GETTERS
	public function name(){return $this->name;}
	public function columns(){return $this->columns;}

	//SETTERS
	public function setName($name){
		if(is_string($name) && !empty($name)){
			$this->name=$name;
		}else{
			throw new Exception("$name is not a string or is empty");
		}
	}

	public function setColumns($columns){

		if(is_array($columns)){
			$this->columns[]=$columns;
		}else{
			throw new Exception("$columns is not an Array");
		}
	}

	public function toColumns(Entity $entity){
  		
  		$properties = $entity->properties();  	
  		
  		foreach ($properties as $key => $property) {
  			$col = new DaoColumn;  			
  			$col->setName($key);
  			$this->columns[$key]=$col;
  		}
		
	}
}