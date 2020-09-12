<?php

namespace FormBuilder;

use FormBuilder\Options\YesNoOptions;
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
        $this->form
            ->add(
                new StringField([
                    'id' => 'name',
                    'label' => 'name',
                    'name' => 'name',
                    'required' => true
                ])
            )->add(
                new StringField([
                    'id' => 'expression',
                    'label' => 'expression (exemple : * 1 * * *)',
                    'name' => 'expression',
                    'title' => 'Saisie au format expression crontab * * * * *',
                    'required' => true
                ])
            )->add(
                new SelectField([
                    'id' => 'active',
                    'label' => 'active',
                    'name' => 'active',
                    'selected' => $this->form()->entity()->isActive(),
                    'options' => YesNoOptions::toArray()
                ])
            )->add(
                new SelectField([
                    'id' => 'executor',
                    'label' => 'executor',
                    'name' => 'executor',
                    'selected' => $this->form()->entity()->getExecutor(),
                    'options' =>  $this->getData('scenarios'),
                    'required' => true
                ])
            );
    }
}
