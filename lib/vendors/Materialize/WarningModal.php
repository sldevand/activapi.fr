<?php
namespace Materialize;
class WarningModal extends Modal{
	
	public function getHtml(){
		
		return '		
		  <div id="'.$this->id().'" class="modal">
			<div class="modal-content">
				<h5>'.$this->title().'</h5>				
			</div>
			<div class="modal-footer">
				<a id="'.$this->id().'OK" class="modal-action waves-effect waves-green btn-flat">Valider</a>
				<a id="'.$this->id().'Cancel" class="modal-action modal-close waves-effect waves-green btn-flat">Annuler</a>
			</div>
		  </div>';	  
	}
	
}