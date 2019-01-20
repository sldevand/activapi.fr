<?php

namespace Materialize;

/**
 * Class FlatButton
 * @package Materialize
 */
class FlatButton extends Button{

    /**
     * @return string
     */
	public function getHtml(){	
		return '<div id="'.$this->id().'" class="waves-effect waves-light flat-btn '.$this->color().'" type="'.$this->type().'">'.$this->getIconHtml().$this->title().'</div>';
	}

    /**
     * @return string
     */
	public function getSubmitHtml(){	
		return '<input id="'.$this->id().'" class="waves-effect waves-light flat-btn '.$this->color().'" value="'.$this->title().'" type="'.$this->type().'" />';
	}
}
