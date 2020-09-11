<?php

namespace FormBuilder\Configuration;

use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class ConfigurationFormBuilder
 * @package FormBuilder\Configuration
 */
class ConfigurationFormBuilder extends FormBuilder
{
    /**
     * @return mixed|void
     */
    public function build()
    {
        $this->form
            ->add(
                new StringField([
                    'id' => 'email-key',
                    'name' => 'key',
                    'hidden' => 'hidden',
                    'value' => 'email'
                ])
            )->add(
                new StringField([
                    'id'    => 'email-value',
                    'label' => 'Value',
                    'name' => 'value',
                    'required' => 'true',
                    'type' => 'email'
                ])
            );
    }
}
