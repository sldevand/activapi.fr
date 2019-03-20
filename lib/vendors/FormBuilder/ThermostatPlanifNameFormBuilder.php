<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class ThermostatPlanifNameFormBuilder
 * @package FormBuilder
 */
class ThermostatPlanifNameFormBuilder extends FormBuilder
{
    /**
     * @return mixed
     */
    public function build()
    {

        $this->form
            ->add(
                new StringField(
                    [
                        'label' => 'nom',
                        'name' => 'nom'
                    ]
                )
            );
    }
}
