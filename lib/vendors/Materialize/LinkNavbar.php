<?php
namespace Materialize;
class LinkNavbar extends Widget{
	
	private $_title;
	private $_link;
	private $_icon;	
	private $_align;	
	
	
	public function __construct($title,$link,$icon=''){		
		$this->setTitle($title);
		$this->setLink($link);
		$this->setIcon($icon);		
	}
	
	public function getHtml(){
		
		$iconHtml = '<i class="material-icons left">'.$this->icon().'</i>';
		
		return '<li><a href="'.$this->_link.'">'.$iconHtml.$this->_title.'</a></li>';
	}	
	
	public function getIconHtml(){
		
		return '<i class="material-icons">'.$this->icon().'</i>';	
	}			
		
	public function title(){return $this->_title;}
	public function icon(){return $this->_icon;}
	public function link(){return $this->_link;}
	
	public function setTitle($title){
		$this->_title=$title;
	}	
	
	public function setIcon($icon){
		$this->_icon=$icon;
	}
	
	public function setLink($link){
		$this->_link=$link;
	}	
}