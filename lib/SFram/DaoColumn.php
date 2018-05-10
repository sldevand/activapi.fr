<?php

namespace SFram;

class DaoColumn{
	
	protected $name;
	protected $type="string";
	protected $length;

	//GETTERS
	public function name(){return $this->name;}
	public function type(){return $this->type;}
	public function length(){return $this->length;}
	
	//SETTERS
	public function setName($name){ 
		$this->name=$name;
	}

	public function setType($type){ 
		$this->type=$type;
	}

	public function setLength($length){ 
		$this->length=$length;
	}

}