<?php

namespace App\Frontend\Modules\Sensors\Config\Form;

use OCFram\FormBuilder;
use OCFram\StringField;
use OCFram\SwitchField;
use Sensors\Helper\Config;

/**
 * Class ConfigurationFormBuilder
 * @package App\Frontend\Modules\Sensors\Config\Form
 */
class ConfigurationFormBuilder extends FormBuilder
{
    const NAME = 'action-sensors-submit';

    /**
     * @return \OCFram\Form
     */
    public function build()
    {
        /** @var \Entity\Configuration\Configuration $enableFieldConfig */
        $enableFieldConfig = $this->getData(Config::PATH_SENSORS_ALERT_ENABLE);
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
            )
            ->add(
                new SwitchField(
                    [
                        'id' => 'action-sensors-enable',
                        'title' => 'Enable mail alerts',
                        'name' => Config::PATH_SENSORS_ALERT_ENABLE,
                        'required' => true,
                        'checked' => $enableFieldConfig == 'yes' ? true : false,
                        'value' => $enableFieldConfig == 'yes' ? 'yes' : 'no',
                        'leftText' => 'No',
                        'rightText' => 'Yes',
                        'wrapper' => 'col s10 m6 l4'
                    ]
                )
            );
    }
}
