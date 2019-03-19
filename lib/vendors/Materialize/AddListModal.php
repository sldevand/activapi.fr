<?php

namespace Materialize;

/**
 * Class AddListModal
 * @package Materialize
 */
class AddListModal extends Modal
{
    /**
     * @return string
     */
    public function getHtml()
    {
        return '		
		  <div id="' . $this->id() . '" class="modal">
			<div class="modal-content">
				<h5>' . $this->title() . '</h5>
				<input id="' . $this->id() . 'Input" type="text" />
			</div>
			<div class="modal-footer">
				<a id="' . $this->id() . 'OK" class="modal-action waves-effect waves-green btn-flat">Valider</a>
				<a id="' . $this->id() . 'Cancel" class="modal-action waves-effect waves-green btn-flat">Annuler</a>
			</div>
		  </div>';
    }
}
