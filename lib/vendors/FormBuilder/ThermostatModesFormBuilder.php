<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\NumberField;
use OCFram\StringField;

/**
 * Class ThermostatModesFormBuilder
 * @package FormBuilder
 */
class ThermostatModesFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $this->form
            ->add(
                new StringField([
                    'label' => 'nom',
                    'name' => 'nom',
                    'required' => 'true'
                ])
            )->add(
                new NumberField([
                    'label' => 'consigne',
                    'name' => 'consigne',
                    'min' => 9,
                    'max' => 30,
                    'step' => 0.5,
                    'required' => 'true'
                ])
            )->add(
                new NumberField([
                    'label' => 'delta',
                    'name' => 'delta',
                    'min' => 0.5,
                    'max' => 1.5,
                    'step' => 0.1,
                    'required' => 'true'
                ])
            );
    }
}
