<?php
namespace Materialize;
class CardPanel extends Widget{
	
	protected $bgColor='teal';
	protected $textColor='white-text';
	protected $shade='';
	protected $content='';
	protected $link;
	
	public function getHtml(){
		
		$htmlReturn='	
		   
		   <div class="square '.$this->bgColor.' '.$this->shade.' ">
		  
		   	<div class="card-content center">
		
				<div class="table ">
					<div class="table-cell">
				
						<span class="'.$this->textColor.' flow-text">'.$this->content().'</span>
			
					</div>
				</div>
				
			</div>	
				
			</div>	
			
        ';  
		return $htmlReturn;		
		
	}	

	
	//GETTERS 
	public function bgColor(){return $this->bgColor;}
	public function textColor(){return $this->textColor;}
	public function shade(){return $this->shade;}	
	public function content(){return $this->content;}
	public function link(){return $this->link;}
	
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

	public function setContent($content){
		if(is_string($content) && !empty($content)){
			$this->content=$content;
		}
	}
	
	public function setLink(LinkNavBar $link){
		
			$this->link=$link;
		
	}
	
	
	
}