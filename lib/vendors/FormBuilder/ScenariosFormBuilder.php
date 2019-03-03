<?php

namespace FormBuilder;

use Entity\Scenario;
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
    public function build()
    {
        $actionneursListRaw = json_decode(json_encode($this->form()->entity()->actionneurs), true);
        $actionneursSelect = [];
        foreach ($actionneursListRaw as $key => $actionneur) {
            $actionneursSelect[$actionneur["id"]] = $actionneur["nom"];
        }

        $sequence = !empty($this->form()->entity()->sequence) ? $this->form()->entity()->sequence : [];

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
                    'label' => 'Nom',
                    'name' => 'nom',
                    'value' => $this->form()->entity()->nom(),
                    'required' => 'true'
                ]),
                'col s8'
            );
        if (empty($sequence)) {
            $this->createNewSequenceFields($actionneursSelect);
        } else {
            $this->createSequenceFields($sequence, $actionneursSelect);
        }
    }

    /**
     * @param array $actionneursSelect
     */
    public function createNewSequenceFields($actionneursSelect)
    {
        $this->form
            ->add(
                new SelectField([
                    'label' => 'Actionneur',
                    'name' => 'actionneurid',
                    'options' => $actionneursSelect,
                    'required' => 'true'
                ]),
                'col s8'
            )->add(
                new NumberField([
                    'label' => 'Etat',
                    'name' => 'etat',
                    'min' => 0,
                    'max' => 255,
                    'step' => 1,
                    'required' => 'true'
                ]),
                'col s4'
            );
    }

    /**
     * @param Scenario[] $sequence
     * @param array $actionneursSelect
     */
    public function createSequenceFields($sequence, $actionneursSelect)
    {
        foreach ($sequence as $item) {
            $this->form
                ->add(
                    new SelectField([
                        'label' => 'Actionneur',
                        'name' => 'actionneurid',
                        'selected' => $item->actionneurId(),
                        'options' => $actionneursSelect,
                        'required' => 'true'
                    ]),
                    'col s8'
                )->add(
                    new NumberField([
                        'label' => 'Etat',
                        'name' => 'etat',
                        'min' => 0,
                        'max' => 255,
                        'step' => 1,
                        'value' => $item->etat(),
                        'required' => 'true'
                    ]),
                    'col s4'
                );
        }
    }
}
