<?php

namespace Materialize;
class RaisedButton extends Button{	
	public function getHtml(){	
		return '<a id="'.$this->id().'" class="waves-effect waves-light btn col s12">'.$this->getIconHtml().$this->title().'</a>';
	}	
}