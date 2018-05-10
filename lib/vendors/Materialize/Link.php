<?php
namespace Materialize;

class Link extends Widget{
	
	protected $_title;
	protected $_link;
	protected $_icon;
	protected $_iconColor;
	protected $_titleColor;
	protected $_align;	
	
	
	public function __construct($title,$link,$icon='',$iconColor='',$titleColor=''){		
		$this->setTitle($title);
		$this->setLink($link);
		$this->setIcon($icon);
		$this->setIconColor($iconColor);	
		$this->setTitleColor($titleColor);		
	}
	
	public function getHtml(){		
	
		
		return '<a href="'.$this->_link.'" class="valign-wrapper">'.
					'<i class="material-icons '.$this->iconColor().' left valign">'.$this->icon().'</i>'.
					'<div class="'.$this->titleColor().' valign">'.$this->title().'</div>
				</a>';
	}

	public function getHtmlForTable(){
		
		$iconHtml = '<i class="material-icons '.$this->iconColor().' left">'.$this->icon().'</i>';
		
		return '<a href="'.$this->_link.'" style="margin:10px;" class="center">'.$iconHtml.$this->title().'</a>';
	}	
		
	public function title(){return $this->_title;}
	public function icon(){return $this->_icon;}
	public function iconColor(){return $this->_iconColor;}
	public function titleColor(){return $this->_titleColor;}
	public function link(){return $this->_link;}
	
	public function setTitle($title){
		$this->_title=$title;
	}	
	
	public function setIcon($icon){
		$this->_icon=$icon;
	}

	public function setIconColor($iconColor){
		$this->_iconColor=$iconColor;
	}

	public function setTitleColor($titleColor){
		$this->_titleColor=$titleColor;
	}
	
	public function setLink($link){
		$this->_link=$link;
	}	
}