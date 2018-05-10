<?php
namespace Materialize;
abstract class Navbar extends Widget{
	
	private $_title;
	private $_logo;
	private $_links=[];
	
	public function title(){return $this->_title;}
	public function logo(){return $this->_logo;}
	public function links(){return $this->_links;}
	
	public function setTitle($title){
		$this->_title=$title;
	}	
	
	public function setLogo($logo){
		$this->_logo=$logo;
	}
	
	public function setLinks($links){
		$this->_links=$links;
	}	
}