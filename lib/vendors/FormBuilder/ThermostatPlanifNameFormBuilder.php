<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\NotNullValidator;
use OCFram\StringField;

/**
 * Class ThermostatPlanifNameFormBuilder
 * @package FormBuilder
 */
class ThermostatPlanifNameFormBuilder extends FormBuilder
{
    /**
     * @return mixed|\OCFram\Form
     */
    public function build()
    {
        return $this->form
            ->add(
                new StringField(
                    [
                        'label' => 'nom',
                        'name' => 'nom',
                        'validators' => [
                            new NotNullValidator('Le nom est vide!')
                        ]
                    ]
                )
            );
    }
}
