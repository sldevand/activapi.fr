<?php
namespace Materialize;

abstract class Button extends Widget{
	
	
	protected $title=null;
	protected $icon=null;
	protected $align='left';	
	protected $href='';	
	protected $type='';
	protected $color='';

	
	public function id(){return $this->id;}
	public function title(){return $this->title;}
	public function icon(){return $this->icon;}
	public function align(){return $this->align;}
	public function href(){return $this->href;}
	public function type(){return $this->type;}
	public function color(){return $this->color;}

	
	
	public function setId($id){
		$this->id=$id;
	}
		
	public function setTitle($title){
		$this->title=$title;
	}
	
	public function setIcon($icon){
		$this->icon=$icon;
	}
	
	public function setAlign($align){
		
		if($align == 'right'){
			$this->align=$align;
		}else {$this->align='left';}
	}

	public function setHref($href){
		$this->href=$href;	
	}

	public function setType($type){
		$this->type=$type;	
	}

	public function setColor($color){
		$this->color=$color;	
	}


	
	public function getIconHtml(){
		$icon = '';
		
		if(!is_null($this->icon())){
			$icon='<i class="material-icons '.$this->align().' '.$this->color().'">'.$this->icon().'</i>';
		}
		
		return $icon;
	}
	
	
	
}