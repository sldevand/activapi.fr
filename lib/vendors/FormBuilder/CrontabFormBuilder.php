<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\SelectField;
use OCFram\StringField;

/**
 * Class CrontabFormBuilder
 * @package FormBuilder
 */
class CrontabFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $yesNo = [
            0 => 'Non',
            1 => 'Oui'
        ];

        $scenarios = $this->getData('scenarios');

        $scenariosOptions = [];
        foreach ($scenarios as $scenario) {
            $scenariosOptions[$scenario->id()] = $scenario->getNom();
        }

        $this->form
            ->add(
                new StringField([
                    'id' => 'name',
                    'label' => 'name',
                    'name' => 'name',
                    'required' => 'true'
                ])
            )->add(
                new StringField([
                    'id' => 'expression',
                    'label' => 'expression (exemple : * 1 * * *)',
                    'name' => 'expression',
                    'title' => 'Saisie au format de crontab * * * * *',
                    'required' => 'true'
                ])
            )->add(
                new SelectField([
                    'id' => 'active',
                    'label' => 'active',
                    'name' => 'active',
                    'selected' => $this->form()->entity()->isActive(),
                    'options' => $yesNo
                ])
            )->add(
                new SelectField([
                    'id' => 'executor',
                    'label' => 'executor',
                    'name' => 'executor',
                    'selected' => $this->form()->entity()->getExecutor(),
                    'options' => $scenariosOptions,
                    'required' => 'true'
                ])
            );
    }
}
