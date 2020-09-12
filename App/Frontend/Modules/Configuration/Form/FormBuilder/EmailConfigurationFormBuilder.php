<?php

namespace  App\Frontend\Modules\Configuration\Form\FormBuilder;

use Helper\Configuration\Config;
use OCFram\FormBuilder;
use OCFram\StringField;

/**
 * Class EmailConfigurationFormBuilder
 * @package App\Frontend\Modules\Configuration\Form\FormBuilder
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
                        'name' => 'action-mailer-submit',
                        'type' => 'text',
                        'value' => '',
                        'hidden' => 'hidden'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Email',
                        'name' => Config::PATH_MAILER_ALERT_EMAIL,
                        'required' => true,
                        'type' => 'email',
                        'value' => $this->getData(Config::PATH_MAILER_ALERT_EMAIL)
                    ]
                )
            );
    }
}
