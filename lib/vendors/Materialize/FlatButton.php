<?php

namespace Materialize;
class FlatButton extends Button{
	public function getHtml(){	
		return '<div id="'.$this->id().'" class="waves-effect waves-light flat-btn '.$this->color().'" type="'.$this->type().'">'.$this->getIconHtml().$this->title().'</div>';
	}	

	public function getSubmitHtml(){	
		return '<input id="'.$this->id().'" class="waves-effect waves-light flat-btn '.$this->color().'" value="'.$this->title().'" type="'.$this->type().'" />';
	}
	
}