<?php

namespace FormBuilder;

use Entity\Scenario;
use Materialize\Button\FlatButton;
use OCFram\FormBuilder;
use OCFram\NumberField;
use OCFram\SelectField;
use OCFram\StringField;

/**
 * Class ScenariosFormBuilder
 * @package FormBuilder
 */
class ScenariosFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $actionneursListRaw = json_decode(json_encode($this->form()->entity()->actionneursList), true);
        $actionneursSelect = [];
        foreach ($actionneursListRaw as $key => $actionneur) {
            $actionneursSelect[$actionneur["id"]] = $actionneur["nom"];
        }

        $this->form
            ->add(
                new StringField([
                    'label' => 'Id',
                    'name' => 'scenarioid',
                    'value' => $this->form()->entity()->scenarioid(),
                    'readonly' => 'true'
                ]),
                'col s4'
            )->add(
                new StringField([
                    'id' => 'nom',
                    'label' => 'Nom',
                    'name' => 'nom',
                    'value' => $this->form()->entity()->nom(),
                    'required' => true
                ]),
                'col s8'
            );
    }
}
