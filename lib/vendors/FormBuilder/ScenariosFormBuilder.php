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

        //$this->createSequenceFields($sequence, $actionneursSelect);
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
                    new StringField([
                        'label' => 'ItemId',
                        'name' => 'actionneurs[id][]',
                        'id' => 'actionneur-id-' . $item->id(),
                        'value' => $item->id(),
                        'required' => 'true',
                        'readonly' => 'true'
                    ]),
                    'col s2'
                )
                ->add(
                    new SelectField([
                        'label' => 'Actionneur',
                        'name' => 'actionneurs[actionneurid][]',
                        'id' => 'actionneur-select-' . $item->id(),
                        'selected' => $item->actionneurId(),
                        'options' => $actionneursSelect,
                        'required' => 'true'
                    ]),
                    'col s6'
                )->add(
                    new NumberField([
                        'label' => 'Etat',
                        'name' => 'actionneurs[etat][]',
                        'id' => 'actionneur-etat-' . $item->id(),
                        'min' => 0,
                        'max' => 255,
                        'step' => 1,
                        'value' => $item->etat(),
                        'required' => 'true'
                    ]),
                    'col s2'
                )->addWidget(
                    new FlatButton(
                        [
                            'id' => 'actionneur-delete-' . $item->id(),
                            'icon' => 'delete',
                            'type' => 'button',
                            'color' => 'secondaryTextColor',
                            'wrapper' => 'col s2'
                        ]
                    ),
                    'actionneur-etat-' . $item->id()
                );
        }
    }
}
