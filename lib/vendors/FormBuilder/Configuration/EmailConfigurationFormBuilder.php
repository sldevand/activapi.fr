<?php

namespace FormBuilder\Configuration;

use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class EmailConfigurationFormBuilder
 * @package FormBuilder\Configuration
 */
class EmailConfigurationFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $this->form
            ->add(
                new StringField(
                    [
                        'name' => 'id',
                        'type' => 'text',
                        'value' => $this->getData('id'),
                        'hidden' => 'hidden'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Email',
                        'name' => 'email',
                        'required' => true,
                        'type' => 'email',
                        'value' => $this->getData('email')
                    ]
                )
            );
    }
}
