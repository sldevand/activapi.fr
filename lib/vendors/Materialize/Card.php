<?php
namespace Materialize;

class Card extends Widget{
	
	protected $bgColor='teal';
	protected $textColor='white-text';
	protected $shade='';
	protected $title='Title';
	protected $content='';
	protected $links=[];
	
	
	
	public function getHtml(){
		
	$htmlReturn='	
		   <div id="'.$this->id().'" class="card  '.$this->shade.'">
		   	  	<div class="card-title '.$this->textColor.' '.$this->bgColor.'">'.$this->title.'</div>
				<div class="card-content">
			
				  '.$this->content.'
				</div>				
				';
				
			foreach($this->links as $link){
				$htmlReturn.= $link->getHtml2();			
			}					
		
			$htmlReturn.='			
				</div>
			  		
        ';  
		return $htmlReturn;		
		
	}
	
	public function addLink(LinkNavbar $link){		
		$this->links[]=$link;		
	}	
	
	//GETTERS 
	public function bgColor(){return $this->bgColor;}
	public function textColor(){return $this->textColor;}
	public function shade(){return $this->shade;}
	public function title(){return $this->title;}
	public function content(){return $this->content;}
	public function links(){return $this->links;}
	
	//SETTERS
	
	public function setBgColor($bgColor){
		if(is_string($bgColor) && !empty($bgColor)){
			$this->bgColor=$bgColor;
		}
	}
	
	public function setTextColor($textColor){
		if(is_string($textColor) && !empty($textColor)){
			$this->textColor=$textColor;
		}
	}
	
	public function setShade($shade){
		if(is_string($shade) && !empty($shade)){
			$this->shade=$shade;
		}
	}
	
	public function setTitle($title){
		if(is_string($title) && !empty($title)){
			$this->title=$title;
		}
	}	
	
	public function setContent($content){
		if(is_string($content) && !empty($content)){
			$this->content=$content;
		}
	}
	
	public function setLinks($links){
		if(is_array($links) && !empty($links)){
			$this->links=$links;
		}
	}
	
	
	
}