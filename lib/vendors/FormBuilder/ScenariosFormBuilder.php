<?php

namespace FormBuilder;

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
        $actionneursRaw = json_decode(json_encode($this->form()->entity()->actionneurs), true);

        $actionneurs = [];
        foreach ($actionneursRaw as $key => $actionneur) {
            $actionneurs[$actionneur["id"]] = $actionneur["nom"];
        }

        $etat = !empty($this->form()->entity()->etat()) ? $this->form()->entity()->etat() : '0';

        $this->form
            ->add(
                new StringField([
                    'label' => 'scenarioid',
                    'name' => 'scenarioid',
                    'value' => $this->form()->entity()->scenarioid(),
                    'hidden' => true
                ])
            )->add(
                new StringField([
                    'label' => 'nom',
                    'name' => 'nom',
                    'value' => $this->form()->entity()->nom(),
                    'required' => true
                ])
            )->add(
                new SelectField([
                    'label' => 'actionneur',
                    'name' => 'actionneurid',
                    'selected' => $this->form()->entity()->actionneurid(),
                    'options' => $actionneurs
                ])
            )->add(
                new NumberField([
                    'label' => 'etat',
                    'name' => 'etat',
                    'min' => 0,
                    'max' => 255,
                    'step' => 1,
                    'value' => $etat
                ])
            );
    }
}