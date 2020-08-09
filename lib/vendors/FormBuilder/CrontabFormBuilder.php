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
                    'label' => 'expression',
                    'name' => 'expression',
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
                new StringField([
                    'id' => 'executor',
                    'label' => 'executor',
                    'name' => 'executor',
                    'required' => 'true'
                ])
            );
    }
}
