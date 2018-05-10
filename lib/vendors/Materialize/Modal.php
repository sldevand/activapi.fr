<?php
namespace Materialize;
abstract class Modal extends Widget{
	
	private $_title;
	
	public function title(){return $this->_title;}
	
		  
	public function setTitle($title){		
		$this->_title=$title;
	}
	
	
	
}