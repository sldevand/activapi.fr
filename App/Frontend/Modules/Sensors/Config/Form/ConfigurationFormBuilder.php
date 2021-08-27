<?php

namespace App\Frontend\Modules\Sensors\Config\Form;

use OCFram\FormBuilder;
use OCFram\NumberField;
use OCFram\StringField;
use OCFram\SwitchField;
use Sensors\Helper\Config;
use Sensors\Helper\Data;

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

        $this->form
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
                        'checked' => $enableFieldConfig === 'yes',
                        'value' => $enableFieldConfig === 'yes' ? 'yes' : 'no',
                        'leftText' => 'No',
                        'rightText' => 'Yes',
                        'wrapper' => 'col s10 m6 l4',
                        'separator' => 'bottom'
                    ]
                )
            );
        $timesConfig = $this->getData(Config::PATH_SENSORS_ALERT_TIMES);
        $times = json_decode($timesConfig->getConfigValue(), true);
        $sensors = $this->getData('sensors');
        $alertTimesPath = Config::PATH_SENSORS_ALERT_TIMES;
        $index = 0;
        foreach ($sensors as $sensor) {
            $this->form
                ->add(
                    new StringField(
                        [
                            'name' => 'sensor-' . $sensor->id(),
                            'type' => 'text',
                            'value' => $sensor->id(),
                            'label' => 'Id',
                            'wrapper' => 'col s2',
                            'readonly' => true
                        ]
                    )
                )
                ->add(
                    new StringField(
                        [
                            'name' => 'sensor-' . $sensor->nom(),
                            'type' => 'text',
                            'value' => $sensor->nom(),
                            'label' => 'Nom',
                            'wrapper' => 'col s6',
                            'readonly' => true
                        ]
                    )
                )
                ->add(
                    new NumberField(
                        array(
                            'name' => $alertTimesPath . '-' . $sensor->id(),
                            'type' => 'text',
                            'value' => $times[$sensor->id()] ?? (string)Data::SENSOR_ACTIVITY_TIME,
                            'label' => 'time (min) ',
                            'min' => 5,
                            'max' => 2880,
                            'wrapper' => 'col s4'

                        )
                    )
                );
            $index++;
        }


        return $this->form;
    }
}
