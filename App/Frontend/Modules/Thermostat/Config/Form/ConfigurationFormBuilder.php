<?php

namespace App\Frontend\Modules\Thermostat\Config\Form;

use OCFram\FormBuilder;
use OCFram\NumberField;
use OCFram\StringField;
use OCFram\SwitchField;
use Thermostat\Helper\Config;

/**
 * Class ConfigurationFormBuilder
 * @package App\Frontend\Modules\Thermostat\Config\Form
 */
class ConfigurationFormBuilder extends FormBuilder
{
    const NAME = 'action-thermostat-submit';

    /**
     * @return \OCFram\Form
     */
    public function build()
    {
        /** @var \Entity\Configuration\Configuration $enableFieldConfig */
        $enableFieldConfig = $this->getData(Config::PATH_THERMOSTAT_ENABLE);
        $enableFieldConfig = $enableFieldConfig ? $enableFieldConfig->getConfigValue() : '';

        /** @var \Entity\Configuration\Configuration $delayFieldConfig */
        $delayFieldConfig = $this->getData(Config::PATH_THERMOSTAT_DELAY);
        $delayFieldConfig = $delayFieldConfig ? $delayFieldConfig->getConfigValue() : '';


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
                        'id' => 'action-thermostat-enable',
                        'title' => 'Enable email alerts',
                        'name' => Config::PATH_THERMOSTAT_ENABLE,
                        'required' => true,
                        'checked' => $enableFieldConfig == 'yes' ? true : false,
                        'value' => $enableFieldConfig == 'yes' ? 'yes' : 'no',
                        'leftText' => 'No',
                        'rightText' => 'Yes',
                        'wrapper' => 'col s10 m4'
                    ]
                )
            )
            ->add(
                new NumberField(
                    [
                        'id' => 'action-thermostat-delay',
                        'label' => 'Delay after power off (minutes)',
                        'name' => Config::PATH_THERMOSTAT_DELAY,
                        'value' => $delayFieldConfig,
                        'min' => 5,
                        'max' => 120,
                        'step' => 1,
                        'required' => true,
                        'wrapper' => 'col s6 m10'
                    ]
                )
            );
    }
}
