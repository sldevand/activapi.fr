<?php

namespace App\Frontend\Modules\Mailer\Config\Form;

use Mailer\Helper\Config;
use OCFram\FormBuilder;
use OCFram\StringField;
use OCFram\SwitchField;

/**
 * Class ConfigurationFormBuilder
 * @package App\Frontend\Modules\Mailer\Config\Form
 */
class ConfigurationFormBuilder extends FormBuilder
{
    const NAME = 'action-mailer-submit';

    /**
     * @return \OCFram\Form
     */
    public function build()
    {
        /** @var \Entity\Configuration\Configuration $emailFieldConfig */
        $emailFieldConfig = $this->getData(Config::PATH_MAILER_ALERT_EMAIL);
        $emailFieldConfigValue = $emailFieldConfig ? $emailFieldConfig->getConfigValue() : '';

        /** @var \Entity\Configuration\Configuration $enableFieldConfig */
        $enableFieldConfig = $this->getData(Config::PATH_MAILER_ALERT_ENABLE);
        $enableFieldConfig = $enableFieldConfig ? $enableFieldConfig->getConfigValue() : '';

        return $this->form
            ->add(
                new StringField(
                    [
                        'name' => self::NAME,
                        'type' => 'text',
                        'value' => self::NAME,
                        'hidden' => 'hidden'
                    ]
                )
            )->add(
                new SwitchField(
                    [
                        'id' => 'action-mailer-enable',
                        'title' => 'Enable',
                        'name' => Config::PATH_MAILER_ALERT_ENABLE,
                        'required' => true,
                        'checked' => $enableFieldConfig == 'yes' ? true : false,
                        'value' => $enableFieldConfig == 'yes' ? 'yes' : 'no',
                        'leftText' => 'No',
                        'rightText' => 'Yes',
                        'wrapper' => 'col s10 m6 l4'
                    ]
                )
            )->add(
                new StringField(
                    [
                        'label' => 'Email',
                        'name' => Config::PATH_MAILER_ALERT_EMAIL,
                        'required' => true,
                        'type' => 'email',
                        'value' => $emailFieldConfigValue
                    ]
                )
            );
    }
}
