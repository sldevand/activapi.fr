<?php
namespace Materialize;

class FloatingActionButton extends Button{	

	private $_fixed=false;
	
	public function fixed(){return $this->_fixed;}
	
	public function setFixed($fixed){		
		if(is_bool($fixed)){			
			$this->_fixed=$fixed;
		}
	}

	public function getHtml(){			
		$fixedHtml='';	
		
		if($this->fixed()){$fixedHtml='<div class="fixed-action-btn">';}
		
		$returnHtml= $fixedHtml.'<a href="'.$this->href().'" id="'.$this->id().'" class="btn-floating btn-large waves-effect waves-light btn secondaryColor">'.$this->getIconHtml().'</a>';
	
		if($this->fixed()){$returnHtml.= '</div>';}		
		
		return $returnHtml;
	}			
}