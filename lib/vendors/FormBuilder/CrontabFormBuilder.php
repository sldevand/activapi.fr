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
     * @return \OCFram\Form
     */
    public function build()
    {
        $executorSelectOptions = array_merge($this->getData('scenarios'), $this->getData('crontab'));

        return $this->form
            ->add(
                new StringField([
                    'id' => 'name',
                    'label' => 'name',
                    'name' => 'name',
                    'value' => $this->form()->entity()->getName(),
                    'required' => true
                ])
            )->add(
                new StringField([
                    'id' => 'expression',
                    'label' => 'expression (exemple : * 1 * * *)',
                    'name' => 'expression',
                    'title' => 'Saisie au format expression crontab * * * * *',
                    'value' => $this->form()->entity()->getExpression(),
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
                    'options' =>  $executorSelectOptions,
                    'required' => true
                ])
            )->add(
                new StringField([
                    'id' => 'args',
                    'label' => 'args (exemple : ["app"])',
                    'name' => 'args',
                    'title' => '',
                    'value' => $this->form()->entity()->getArgs(),
                    'required' => false
                ])
            );
    }
}
